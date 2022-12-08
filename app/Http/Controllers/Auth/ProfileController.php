<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Show the form for creating the resource.
     *
     * @codeCoverageIgnore
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Store the newly created resource in storage.
     *
     * @codeCoverageIgnore
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort(404);
    }

    /**
     * 마이페이지
     *
     * @return \Illuminate\View\View
     */
    public function show(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        return view('auth.profile.show', [
            'user' => $user,
        ]);
    }

    /**
     * 마이페이지 - 개인정보수정
     *
     * @return \Illuminate\View\View
     */
    public function edit(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        return view('auth.profile.edit', [
            'user' => $user,
        ]);
    }

    /**
     * 개인정보수정
     *
     * @param  \App\Http\Requests\UpdateProfileRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateProfileRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $data = $request->only('name');

        if ($request->filled('password')) {
            $data = [
                ...$data,
                'password' => Hash::make($request->password),
            ];
        }

        $user->update($data);

        return redirect()->to('/');
    }

    /**
     * Remove the resource from storage.
     *
     * @codeCoverageIgnore
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        abort(404);
    }
}
