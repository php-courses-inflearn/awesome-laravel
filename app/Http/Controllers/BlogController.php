<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Models\Blog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    /**
     * BlogController
     */
    public function __construct()
    {
        $this->authorizeResource(Blog::class, 'blog');
    }

    /**
     * 블로그 목록
     */
    public function index(): View
    {
        return view('blogs.index', [
            'blogs' => Blog::with('user')->paginate(5),
        ]);
    }

    /**
     * 블로그 생성 폼
     */
    public function create(): View
    {
        return view('blogs.create');
    }

    /**
     * 블로그 생성
     */
    public function store(StoreBlogRequest $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $user->blogs()->create($request->validated());

        return to_route('dashboard.blogs');
    }

    /**
     * 블로그
     */
    public function show(Request $request, Blog $blog): View
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        return view('blogs.show', [
            'blog' => $blog,
            'owned' => $user->blogs()->find($blog->id),
            'subscribed' => $blog->subscribers()->find($user->id),
            'posts' => $blog->posts()->latest()->paginate(5),
        ]);
    }

    /**
     * 블로그 수정 폼
     */
    public function edit(Blog $blog): View
    {
        return view('blogs.edit', [
            'blog' => $blog->load([
                'comments.user',
                'comments.commentable',
            ]),
        ]);
    }

    /**
     * 블로그 수정
     */
    public function update(UpdateBlogRequest $request, Blog $blog): RedirectResponse
    {
        $blog->update($request->validated());

        return to_route('dashboard.blogs');
    }

    /**
     * 블로그 삭제
     */
    public function destroy(Blog $blog): RedirectResponse
    {
        $blog->delete();

        return to_route('dashboard.blogs');
    }
}
