<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * 사용자 정보 폼
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function dashboard(Request $request)
    {
        return view('dashboard.profile', [
            'user' => $request->user()
        ]);
    }

    /**
     * 사용자 정보 갱신
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'password' => 'nullable|confirmed|max:255',
        ]);

        $request->validate([
            'password' => [Password::defaults()]
        ]);

        $user = $request->user();
        $data = $request->only('name');

        if ($request->filled('password')) {
            $data = $data + ['password' => Hash::make($request->password)];
        }

        $user->update($data);

        return to_route('home');
    }

    /**
     * 회원탈퇴
     *
     * @return void
     */
    public function destroy(Request $request)
    {
        $user = $request->user();

        $user->delete();

        return to_route('home');
    }
}
