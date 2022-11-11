<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    /**
     * 피드
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function __invoke(Request $request)
    {
        $user = $request->user();

        $posts = $user->subscriptions()->exists()
            ? $user->subscriptions()->with('posts', fn ($query) => $query->limit(20))->get()->feed()
            : Post::latest()->limit(20)->get();

        return view('welcome', [
            'posts' => $posts->paginate(5, $request->page ?? 1),
            //'posts' => $this->paginate($posts, $posts->count(), 5, $request->page ?? 1)
        ]);
    }
}
