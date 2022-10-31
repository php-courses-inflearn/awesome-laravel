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
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateUserRequest $request)
    {
        $user = $request->user();
        $data = $request->only('name');

        if ($request->filled('password')) {
            $data = $data + ['password' => Hash::make($request->password)];
        }

        $user->update($data);

        return redirect()->to('/');
    }

    /**
     * 회원탈퇴
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        $user = $request->user();

        $user->delete();

        return redirect()->to('/');
    }
}
