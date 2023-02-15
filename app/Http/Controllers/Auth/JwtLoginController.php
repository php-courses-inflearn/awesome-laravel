<?php

namespace App\Http\Controllers\Auth;

use PHPOpenSourceSaver\JWTAuth\JWTGuard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\JwtLoginRequest;

class JwtLoginController extends Controller
{
    /**
     * JWT 생성
     *
     * @param  \App\Http\Requests\JwtLoginRequest  $request
     * @return \Illuminate\Http\JsonResponse
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
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(): JsonResponse
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    /**
     * JWT 제거
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(): Response
    {
        $guard = $this->guard();

        $guard->logout();
        $guard->invalidate();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Guard
     *
     * @return \PHPOpenSourceSaver\JWTAuth\JWTGuard
     */
    private function guard(): JWTGuard
    {
        /** @var \PHPOpenSourceSaver\JWTAuth\JWTGuard $guard */
        $guard = auth('api');

        return $guard;
    }

    /**
     * 토큰 응답
     *
     * @param $token
     * @return \Illuminate\Http\JsonResponse
     */
    private function respondWithToken($token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60,
        ]);
    }
}
