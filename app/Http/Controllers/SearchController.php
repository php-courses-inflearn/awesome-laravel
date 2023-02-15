<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Http\Requests\SearchRequest;
use App\Models\Post;

class SearchController extends Controller
{
    /**
     * 검색
     */
    public function __invoke(SearchRequest $request): View
    {
        $query = $request->input('query');

        return view('search', [
            'posts' => Post::search($query)->get(),
            'query' => $query,
        ]);
    }
}
