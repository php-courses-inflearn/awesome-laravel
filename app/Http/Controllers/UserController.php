<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * 사용자 정보 갱신
     *
     * @param  \App\Http\Requests\UpdateUserRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateUserRequest $request)
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
     * 회원탈퇴
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $user->delete();

        return redirect()->to('/');
    }
}
