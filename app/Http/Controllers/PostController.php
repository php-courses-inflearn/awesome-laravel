<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PostController extends Controller
{
    /**
     * PostController
     */
    public function __construct()
    {
        $this->authorizeResource(Post::class, 'post');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Blog $blog)
    {
        return view('blogs.posts.index', [
            'posts' => $blog->posts()->latest()->paginate()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Blog $blog)
    {
        return view('blogs.posts.create', [
           'blog' => $blog
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request, Blog $blog)
    {
        $post = $blog->posts()->create(
            $request->only('title', 'content')
        );

        $this->attachments($request, $post);

        return to_route('posts.show', $post->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return view('blogs.posts.show', [
            'post' => $post->loadCount('comments'),
            'prev' => Post::where('id', '<', $post->id)
                ->where('blog_id', $post->blog->id)
                ->orderByDesc('id')
                ->first(),
            'next' => Post::where('id', '>', $post->id)
                ->where('blog_id', $post->blog->id)
                ->first(),
            'comments' => $post->comments()
                ->doesntHave('parent')
                ->with(['user', 'replies.user'])
                ->get()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        return view('blogs.posts.edit', [
            'post' => $post
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePostRequest  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $post->update(
            $request->only(['title', 'content'])
        );

        $this->attachments($request, $post);

        return to_route('posts.show', $post->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return to_route('blogs.posts.index', $post->blog->name);
    }

    /**
     * 파일 업로드
     *
     * @param Request $request
     * @param $post
     * @return void
     */
    private function attachments(Request $request, $post)
    {
        if ($request->hasFile('attachments')) {
            app(AttachmentController::class)->store($request, $post);
        }
    }
}
