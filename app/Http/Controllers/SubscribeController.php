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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $blog = Blog::findOrFail($request->blog_id);

        $user->subscriptions()->attach($blog->id);

        event(new Subscribed($user, $blog));

        return back();
    }

    /**
     * 구독 취소
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $blog = Blog::findOrFail($request->blog_id);

        $user->subscriptions()->detach($blog->id);

        return back();
    }
}
