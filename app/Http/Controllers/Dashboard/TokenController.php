<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TokenController extends Controller
{
    /**
     * 토큰 대시보드
     *
     * @codeCoverageIgnore
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function __invoke(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        return view('dashboard.tokens', [
            'tokens' => $user->tokens,
        ]);
    }
}
