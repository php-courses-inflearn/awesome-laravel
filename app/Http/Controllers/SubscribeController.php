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
     * @param  Request  $request
     * @param  Blog  $blog
     * @return \Illuminate\Http\RedirectResponse
     */
    public function subscribe(Request $request, Blog $blog)
    {
        $user = $request->user();

        $blog->subscribers()->attach($user->id);

        event(new Subscribed($user, $blog));

        return back();
    }

    /**
     * 구독취소
     *
     * @param  Request  $request
     * @param  Blog  $blog
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unsubscribe(Request $request, Blog $blog)
    {
        $user = $request->user();

        $blog->subscribers()->detach($user->id);

        return back();
    }
}
