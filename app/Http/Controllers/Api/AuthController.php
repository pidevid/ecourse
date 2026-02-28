<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
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
                'roles'              => $user->getRoleNames(),
                'has_website_access' => $user->has_website_access,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully.']);
    }

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
            'roles'              => $user->getRoleNames(),
            'has_website_access' => $user->has_website_access,
            'has_website'        => $user->personalWebsite !== null,
            'portfolio_url'      => $user->username ? url('/portfolio/' . $user->username) : null,
        ]);
    }
}
