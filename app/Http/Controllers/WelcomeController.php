<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Post;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class WelcomeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $posts = Post::latest()->limit(20)->get();

        if (auth()->check()) {
            $user = $request->user();

            if ($user->subscriptions()->exists()) {
                $posts = $this->feed($user, 5);
            }
        }

        return view('welcome', [
            'posts' => $posts->paginate(5, $request->page ?? 1)
            //'posts' => $this->paginate($posts, $posts->count(), 5, $request->page ?? 1)
        ]);
    }

    /**
     * 피드
     *
     * @param User $user
     * @param int $count
     * @return mixed
     */
    private function feed(User $user, int $count)
    {
        return $user->subscriptions
            ->reduce(function (Collection $feed, Blog $subscription) use ($count) {
                $posts = $subscription->posts()->latest()->limit($count)->get();

                return $feed->merge($posts);
            }, collect())
            ->sort(function ($a, $b) {
                return $a['created_at']->lessThan($b['created_at']);
            });
    }

//    /**
//     * 페이지네이션
//     *
//     * @param Collection $items
//     * @param int $total
//     * @param int $perPage
//     * @param int $currentPage
//     * @param array $options
//     * @return \Illuminate\Contracts\Foundation\Application|mixed
//     */
//    private function paginate(Collection $items, int $total, int $perPage, int $currentPage, array $options = [])
//    {
//        return app(LengthAwarePaginator::class, [
//            'items' => $items->forPage($currentPage, $perPage),
//            'total' => $total,
//            'perPage' => $perPage,
//            'currentPage' => $currentPage,
//            'options' => $options,
//        ]);
//    }
}


