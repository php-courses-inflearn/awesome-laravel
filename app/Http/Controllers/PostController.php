<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Blog;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    /**
     * PostController
     */
    public function __construct(private readonly PostService $postService)
    {
        $this->authorizeResource(Post::class, 'post', [
            'except' => ['create', 'store'],
        ]);

        $this->middleware('can:create,App\Models\Post,blog')
            ->only(['create', 'store']);
    }

    /**
     * 글 목록
     */
    public function index(Blog $blog): View
    {
        return view('blogs.posts.index', [
            'posts' => $blog->posts()->latest()->paginate(),
        ]);
    }

    /**
     * 글 쓰기 폼
     */
    public function create(Blog $blog): View
    {
        return view('blogs.posts.create', [
            'blog' => $blog,
        ]);
    }

    /**
     * 글 쓰기
     */
    public function store(StorePostRequest $request, Blog $blog): RedirectResponse
    {
        $post = $this->postService->store($request->validated(), $blog);

        return to_route('posts.show', $post);
    }

    /**
     * 글 읽기
     */
    public function show(Request $request, Post $post): View
    {
        return view('blogs.posts.show', [
            'post' => $post->loadCount('comments'),
            'comments' => $post->comments()
                ->doesntHave('parent')
                ->with(['user', 'replies.user'])
                ->get(),
        ]);
    }

    /**
     * 글 수정 폼
     */
    public function edit(Post $post): View
    {
        return view('blogs.posts.edit', [
            'post' => $post,
        ]);
    }

    /**
     * 글 수정
     */
    public function update(UpdatePostRequest $request, Post $post): RedirectResponse
    {
        $this->postService->update($request->validated(), $post);

        return to_route('posts.show', $post);
    }

    /**
     * 글 삭제
     */
    public function destroy(Post $post): RedirectResponse
    {
        $this->postService->destroy($post);

        return to_route('blogs.posts.index', $post->blog);
    }
}
