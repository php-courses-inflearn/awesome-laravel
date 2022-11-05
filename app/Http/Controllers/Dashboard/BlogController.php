<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * 블로그 수정 폼
     *
     * @return \Illuminate\View\View
     */
    public function dashboard(Request $request)
    {
        $user = $request->user();

        return view('dashboard.blogs', [
            'blogs' => $user->blogs,
        ]);
    }
}
