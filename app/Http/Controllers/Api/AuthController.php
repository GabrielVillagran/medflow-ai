<?php

namespace App\Http\Controllers\Api;

use App\Actions\Auth\IssueAccessToken;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(
        RegisterRequest $request,
        IssueAccessToken $issueAccessToken,
    ): JsonResponse {
        $user = new User([
            'name' => $request->string('name')->toString(),
            'email' => $request->string('email')->lower()->toString(),
            'password' => $request->string('password')->toString(),
        ]);

        $user->role = UserRole::Patient;
        $user->save();

        $token = $issueAccessToken->handle(
            user: $user,
            deviceName: $request->string('device_name')->toString(),
        );

        return response()->json([
            'message' => 'Registration completed successfully.',
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => new UserResource($user),
        ], 201);
    }

    public function login(
        LoginRequest $request,
        IssueAccessToken $issueAccessToken,
    ): JsonResponse {
        $user = User::query()
            ->where('email', $request->string('email')->lower()->toString())
            ->first();

        if (
            $user === null
            || ! Hash::check(
                $request->string('password')->toString(),
                $user->password,
            )
        ) {
            throw ValidationException::withMessages([
                'email' => [
                    'The provided credentials are incorrect.',
                ],
            ]);
        }

        $token = $issueAccessToken->handle(
            user: $user,
            deviceName: $request->string('device_name')->toString(),
        );

        return response()->json([
            'message' => 'Login completed successfully.',
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => new UserResource($user),
        ]);
    }

    public function me(Request $request): UserResource
    {
        return new UserResource(
            $request->user(),
        );
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()
            ->currentAccessToken()
            ?->delete();

        return response()->json([
            'message' => 'Logout completed successfully.',
        ]);
    }
}
