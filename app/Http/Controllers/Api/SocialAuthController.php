<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Step 1 — GET /api/auth/{provider}/redirect
     *
     * Frontend redirect browser ke URL ini.
     * Provider: google | github
     */
    public function redirect($provider)
    {
        $this->validateProvider($provider);

        return Socialite::driver($provider)
            ->stateless()
            ->redirect();
    }

    /**
     * Step 2 — GET /api/auth/{provider}/callback
     *
     * OAuth provider redirect ke sini setelah user approve.
     * Kita buat/cari user → buat Sanctum token → redirect ke frontend dengan token.
     *
     * Frontend (Next.js) tangkap token dari query param:
     *   http://localhost:3000/auth/callback?token=xxx
     */
    public function callback($provider)
    {
        $this->validateProvider($provider);

        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();
        } catch (Exception $e) {
            $frontendUrl = env('FRONTEND_URL', 'http://localhost:3000');
            return redirect($frontendUrl . '/auth/callback?error=' . urlencode('OAuth gagal: ' . $e->getMessage()));
        }

        $user = $this->findOrCreateUser($socialUser, $provider);

        // Hapus token lama dengan nama yang sama agar tidak numpuk
        $user->tokens()->where('name', 'social-token')->delete();
        $token = $user->createToken('social-token')->plainTextToken;

        $frontendUrl = env('FRONTEND_URL', 'http://localhost:3000');

        return redirect($frontendUrl . '/auth/callback?' . http_build_query([
            'token'    => $token,
            'name'     => $user->name,
            'email'    => $user->email,
            'avatar'   => $user->avatar,
            'provider' => $provider,
        ]));
    }

    /**
     * Step 2 (alternatif) — POST /api/auth/{provider}/token
     *
     * Untuk Next.js yang sudah punya access_token dari SDK Google/GitHub sendiri.
     * Frontend kirim access_token → backend verifikasi → return Sanctum token.
     *
     * Body: { "access_token": "..." }
     */
    public function tokenLogin(Request $request, $provider)
    {
        $this->validateProvider($provider);

        $request->validate([
            'access_token' => 'required|string',
        ]);

        try {
            $socialUser = Socialite::driver($provider)
                ->stateless()
                ->userFromToken($request->access_token);
        } catch (Exception $e) {
            return response()->json(['message' => 'Token OAuth tidak valid.'], 401);
        }

        $user  = $this->findOrCreateUser($socialUser, $provider);
        $user->tokens()->where('name', 'social-token')->delete();
        $token = $user->createToken('social-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => [
                'id'             => $user->id,
                'name'           => $user->name,
                'email'          => $user->email,
                'username'       => $user->username,
                'avatar'         => $user->avatar,
                'email_verified' => $user->hasVerifiedEmail(),
                'roles'          => $user->getRoleNames(),
                'provider'       => $provider,
            ],
        ]);
    }

    // ────────────────────────────────────────────────────────────

    private function findOrCreateUser($socialUser, string $provider): User
    {
        // Cari social account yang sudah ada
        $socialAccount = SocialAccount::where('provider_id', $socialUser->getId())
            ->where('provider_name', $provider)
            ->first();

        if ($socialAccount) {
            return $socialAccount->user;
        }

        // Cari user berdasarkan email
        $user = User::where('email', $socialUser->getEmail())->first();

        if (! $user) {
            $user = User::create([
                'name'              => $socialUser->getName() ?? $socialUser->getNickname() ?? 'User',
                'email'             => $socialUser->getEmail(),
                'password'          => Hash::make(Str::random(32)),
                'email_verified_at' => now(), // social login = email sudah verified
            ]);
            $user->assignRole('member');
        } else {
            // Pastikan punya role member
            if (! $user->hasRole('member') && ! $user->hasRole('admin') && ! $user->hasRole('author')) {
                $user->assignRole('member');
            }
            // Tandai email verified jika belum
            if (! $user->hasVerifiedEmail()) {
                $user->markEmailAsVerified();
            }
        }

        // Simpan social account
        $user->socialAccounts()->create([
            'provider_id'   => $socialUser->getId(),
            'provider_name' => $provider,
        ]);

        return $user;
    }

    private function validateProvider(string $provider): void
    {
        abort_unless(in_array($provider, ['google', 'github']), 400, 'Provider tidak didukung. Gunakan: google atau github.');
    }
}
