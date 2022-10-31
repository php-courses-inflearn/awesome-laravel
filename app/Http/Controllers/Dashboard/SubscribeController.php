<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscribeController extends Controller
{
    /**
     * 내 구독자
     *
     * @param  Request  $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function subscribers(Request $request)
    {
        $user = $request->user();

        return view('dashboard.subscribers', [
            'blogs' => $user->blogs()->with('subscribers')->get(),
        ]);
    }

    /**
     * 내가 구독한 블로그
     *
     * @param  Request  $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function subscriptions(Request $request)
    {
        $user = $request->user();

        return view('dashboard.subscriptions', [
            'blogs' => $user->subscriptions,
        ]);
    }
}
