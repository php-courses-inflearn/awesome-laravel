<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Blog;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * @param  \App\Services\PostService  $postService
     */
    public function __construct(private readonly PostService $postService)
    {
        $this->authorizeResource(Post::class, 'post');
    }

    /**
     * 글 목록
     *
     * @return \Illuminate\View\View
     */
    public function index(Blog $blog)
    {
        return view('blogs.posts.index', [
            'posts' => $blog->posts()->latest()->paginate(),
        ]);
    }

    /**
     * 글 쓰기 폼
     *
     * @return \Illuminate\View\View
     */
    public function create(Blog $blog)
    {
        return view('blogs.posts.create', [
            'blog' => $blog,
        ]);
    }

    /**
     * 글 쓰기
     *
     * @param  \App\Http\Requests\StorePostRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StorePostRequest $request, Blog $blog)
    {
        $post = $this->postService->store($request, $blog);

        return to_route('posts.show', $post->id);
    }

    /**
     * 글 읽기
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\View\View
     */
    public function show(Request $request, Post $post)
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
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\View\View
     */
    public function edit(Post $post)
    {
        return view('blogs.posts.edit', [
            'post' => $post,
        ]);
    }

    /**
     * 글 수정
     *
     * @param  \App\Http\Requests\UpdatePostRequest  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $this->postService->update($request, $post);

        return to_route('posts.show', $post->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Post $post)
    {
        $this->postService->destroy($post);

        return to_route('blogs.posts.index', $post->blog->name);
    }
}
