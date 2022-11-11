<?php

namespace App\Http\Controllers;

use App\Events\Subscribed;
use App\Models\Blog;
use Illuminate\Http\Request;

class SubscribeController extends Controller
{
    /**
     * 구독
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Blog $blog)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $blog->subscribers()->attach($user->id);

        event(new Subscribed($user, $blog));

        return back();
    }

    /**
     * 구독 취소
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, Blog $blog)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $blog->subscribers()->detach($user->id);

        return back();
    }
}
