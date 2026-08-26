<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\JWTGuard;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'in:member,trainer'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        $token = $this->jwtGuard()->login($user);

        return $this->tokenResponse($token, $user, 201);
    }

    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! $token = $this->jwtGuard()->attempt($credentials)) {
            return response()->json(['message' => 'Invalid email or password.'], 401);
        }

        return $this->tokenResponse($token, auth('api')->user());
    }

    public function user(): JsonResponse
    {
        return response()->json(['data' => $this->userPayload($this->jwtGuard()->user())]);
    }

    public function logout(): JsonResponse
    {
        $this->jwtGuard()->logout();

        return response()->json(['data' => null, 'message' => 'Logged out successfully.']);
    }

    public function refresh(Request $request): JsonResponse
    {
        $validated = $request->validate(['refreshToken' => ['required', 'string']]);
        $newToken = $this->jwtGuard()->setToken($validated['refreshToken'])->refresh();

        return $this->tokenResponse($newToken, $this->jwtGuard()->setToken($newToken)->user());
    }

    private function tokenResponse(string $token, User $user, int $status = 200): JsonResponse
    {
        return response()->json([
            'data' => [
                'accessToken' => $token,
                // JWT refresh invalidates the old token, so retain the latest token for future refreshes.
                'refreshToken' => $token,
                'expiresIn' => $this->jwtGuard()->factory()->getTTL() * 60,
                'user' => $this->userPayload($user),
            ],
        ], $status);
    }

    private function userPayload(User $user): array
    {
        return [
            'id' => (string) $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'createdAt' => $user->created_at?->toISOString(),
        ];
    }

    private function jwtGuard(): JWTGuard
    {
        /** @var JWTGuard $guard */
        $guard = auth('api');

        return $guard;
    }
}
