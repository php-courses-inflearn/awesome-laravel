<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\JwtLoginRequest;
use Illuminate\Http\JsonResponse;
use PHPOpenSourceSaver\JWTAuth\JWTGuard;

class JwtLoginController extends Controller
{
    /**
     * JWT 생성
     */
    public function store(JwtLoginRequest $request): JsonResponse
    {
        if (! $token = $this->guard()->attempt($request->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * JWT 갱신
     */
    public function update(): JsonResponse
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    /**
     * JWT 제거
     */
    public function destroy(): JsonResponse
    {
        $guard = $this->guard();

        $guard->logout();
        $guard->invalidate();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Guard
     */
    private function guard(): JWTGuard
    {
        /** @var \PHPOpenSourceSaver\JWTAuth\JWTGuard $guard */
        $guard = auth('api');

        return $guard;
    }

    /**
     * 토큰 응답
     */
    private function respondWithToken(string $token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60,
        ]);
    }
}
