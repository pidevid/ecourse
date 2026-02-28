<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // ────────────────────────────────────────────────────────────
    // POST /api/auth/register
    // ────────────────────────────────────────────────────────────
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Role otomatis member
        $user->assignRole('member');

        // Kirim email verifikasi
        event(new Registered($user));

        return response()->json([
            'message' => 'Registrasi berhasil! Silakan cek email untuk verifikasi akun.',
            'user'    => [
                'id'               => $user->id,
                'name'             => $user->name,
                'email'            => $user->email,
                'email_verified'   => false,
                'roles'            => $user->getRoleNames(),
            ],
        ], 201);
    }

    // ────────────────────────────────────────────────────────────
    // POST /api/auth/login
    // ────────────────────────────────────────────────────────────
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }

        $token = $user->createToken('backoffice-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => [
                'id'                 => $user->id,
                'name'               => $user->name,
                'email'              => $user->email,
                'username'           => $user->username,
                'avatar'             => $user->avatar,
                'email_verified'     => $user->hasVerifiedEmail(),
                'roles'              => $user->getRoleNames(),
                'has_website_access' => (bool) $user->has_website_access,
            ],
        ]);
    }

    // ────────────────────────────────────────────────────────────
    // POST /api/auth/logout
    // ────────────────────────────────────────────────────────────
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout berhasil.']);
    }

    // ────────────────────────────────────────────────────────────
    // GET /api/auth/me
    // ────────────────────────────────────────────────────────────
    public function me(Request $request)
    {
        $user = $request->user()->load('personalWebsite');

        return response()->json([
            'id'                 => $user->id,
            'name'               => $user->name,
            'email'              => $user->email,
            'username'           => $user->username,
            'avatar'             => $user->avatar,
            'github'             => $user->github,
            'instagram'          => $user->instagram,
            'about'              => $user->about,
            'email_verified'     => $user->hasVerifiedEmail(),
            'roles'              => $user->getRoleNames(),
            'has_website_access' => (bool) $user->has_website_access,
            'has_website'        => $user->personalWebsite !== null,
            'portfolio_url'      => $user->username ? url('/portfolio/' . $user->username) : null,
        ]);
    }

    // ────────────────────────────────────────────────────────────
    // POST /api/auth/email/resend
    // ────────────────────────────────────────────────────────────
    public function resendVerification(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email sudah terverifikasi.'], 422);
        }

        $user->sendEmailVerificationNotification();

        return response()->json(['message' => 'Link verifikasi sudah dikirim ulang ke email kamu.']);
    }

    // ────────────────────────────────────────────────────────────
    // GET /api/auth/verify-email/{id}/{hash}
    // ────────────────────────────────────────────────────────────
    public function verifyEmail(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if (! hash_equals(sha1($user->getEmailForVerification()), $hash)) {
            return response()->json(['message' => 'Link verifikasi tidak valid.'], 403);
        }

        if ($user->hasVerifiedEmail()) {
            return redirect(env('FRONTEND_URL', 'http://localhost:3000') . '/email-verified?status=already');
        }

        $user->markEmailAsVerified();
        event(new Verified($user));

        return redirect(env('FRONTEND_URL', 'http://localhost:3000') . '/email-verified?status=success');
    }

    // ────────────────────────────────────────────────────────────
    // POST /api/auth/forgot-password
    // ────────────────────────────────────────────────────────────
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink($request->only('email'));

        if ($status !== Password::RESET_LINK_SENT) {
            return response()->json([
                'message' => 'Email tidak ditemukan atau gagal mengirim link.',
            ], 422);
        }

        return response()->json([
            'message' => 'Link reset password sudah dikirim ke email kamu.',
        ]);
    }

    // ────────────────────────────────────────────────────────────
    // POST /api/auth/reset-password
    // ────────────────────────────────────────────────────────────
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required|string',
            'email'    => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill(['password' => Hash::make($password)])
                     ->setRememberToken(Str::random(60));
                $user->save();
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            return response()->json([
                'message' => 'Token tidak valid atau sudah kadaluarsa.',
            ], 422);
        }

        return response()->json([
            'message' => 'Password berhasil direset. Silakan login.',
        ]);
    }
}
