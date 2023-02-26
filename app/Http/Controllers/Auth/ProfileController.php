<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show the form for creating the resource.
     *
     * @codeCoverageIgnore
     */
    public function create(): never
    {
        abort(404);
    }

    /**
     * Store the newly created resource in storage.
     *
     * @codeCoverageIgnore
     */
    public function store(Request $request): never
    {
        abort(404);
    }

    /**
     * 마이페이지
     */
    public function show(Request $request): View
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        return view('auth.profile.show', [
            'user' => $user,
        ]);
    }

    /**
     * 마이페이지 - 개인정보수정
     */
    public function edit(Request $request): View
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        return view('auth.profile.edit', [
            'user' => $user,
        ]);
    }

    /**
     * 개인정보수정
     */
    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $data = $request->validated();

        if ($request->filled('password')) {
            $data = [
                ...$data,
                'password' => Hash::make($request->password),
            ];
        }

        $user->update($data);

        return to_route('profile.show');
    }

    /**
     * Remove the resource from storage.
     *
     * @codeCoverageIgnore
     */
    public function destroy(): never
    {
        abort(404);
    }
}
