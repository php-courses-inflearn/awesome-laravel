<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Ability;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTokenRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Laravel\Sanctum\PersonalAccessToken;

class TokenController extends Controller
{
    /**
     * 토큰 생성 폼
     */
    public function create(): View
    {
        return view('tokens.create', [
            'abilities' => Ability::cases(),
        ]);
    }

    /**
     * 토큰 생성
     */
    public function store(StoreTokenRequest $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $token = $user->createToken($request->name, $request->abilities);

        return back()
            ->with('status', $token->plainTextToken);
    }

    /**
     * 토큰 삭제
     */
    public function destroy(Request $request, PersonalAccessToken $token): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $user->tokens()->where('id', $token->id)->delete();

        return back();
    }
}
