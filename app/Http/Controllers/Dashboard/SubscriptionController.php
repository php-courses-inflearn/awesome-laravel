<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * 내가 구독한 블로그 대시보드
     *
     * @codeCoverageIgnore
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function __invoke(Request $request): View
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        return view('dashboard.subscriptions', [
            'blogs' => $user->subscriptions,
        ]);
    }
}
