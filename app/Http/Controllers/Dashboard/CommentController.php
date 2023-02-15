<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * 댓글 대시보드
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

        return view('dashboard.comments', [
            'comments' => $user->comments()->with('commentable')->get(),
        ]);
    }
}
