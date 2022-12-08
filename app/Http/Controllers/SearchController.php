<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * 검색
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function __invoke(Request $request)
    {
        $query = $request->input('query');

        return view('search', [
            'q' => $query,
            'posts' => Post::search($query)->get(),
        ]);
    }
}
