<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use App\Events\Subscribed;
use App\Http\Requests\SubscribeRequest;
use App\Http\Requests\UnsubscribeRequest;
use App\Models\Blog;

class SubscribeController extends Controller
{
    /**
     * 구독
     *
     * @param  \App\Http\Requests\SubscribeRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SubscribeRequest $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $blog = Blog::find($request->blog_id);

        $user->subscriptions()->attach($blog->id);

        event(new Subscribed($user, $blog));

        return back();
    }

    /**
     * 구독 취소
     *
     * @param  \App\Http\Requests\UnsubscribeRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(UnsubscribeRequest $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $blog = Blog::find($request->blog_id);

        $user->subscriptions()->detach($blog->id);

        return back();
    }
}
