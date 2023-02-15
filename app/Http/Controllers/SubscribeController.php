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
