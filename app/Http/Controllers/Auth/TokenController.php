<?php

namespace App\Http\Controllers\Auth;

use App\Enums\TokenAbility;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTokenRequest;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class TokenController extends Controller
{
    /**
     * 토큰 생성 폼
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('tokens.create', [
            'abilities' => TokenAbility::cases(),
        ]);
    }

    /**
     * 토큰 생성
     *
     * @param  \App\Http\Requests\StoreTokenRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreTokenRequest $request)
    {
        $token = $request->user()
            ->createToken($request->name, $request->abilities);

        return back()
            ->with('status', $token->plainTextToken);
    }

    /**
     * 토큰 삭제
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Laravel\Sanctum\PersonalAccessToken  $token
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, PersonalAccessToken $token)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $user->tokens()->where('id', $token->id)->delete();

        return back();
    }
}
