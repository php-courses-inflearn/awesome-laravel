<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\JWTGuard;

class JwtLoginController extends Controller
{
    /**
     * JWT 생성
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (! $token = $this->guard()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * JWT 갱신
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    /**
     * JWT 제거
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy()
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
    private function guard()
    {
        /** @var JWTGuard $guard */
        $guard = auth('api');

        return $guard;
    }

    /**
     * 토큰 응답
     *
     * @param $token
     * @return \Illuminate\Http\JsonResponse
     */
    private function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60,
        ]);
    }
}
