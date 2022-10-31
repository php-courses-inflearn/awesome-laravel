<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Models\Blog;
use Illuminate\Http\Request;

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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('blogs.index', [
            'blogs' => Blog::with('user')->paginate(5),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('blogs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreBlogRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreBlogRequest $request)
    {
        $user = $request->user();

        $user->blogs()->create(
            $request->only(['name', 'display_name'])
        );

        return to_route('dashboard.blogs');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Blog $blog)
    {
        $user = $request->user();

        return view('blogs.show', [
            'blog' => $blog,
            'owned' => $user->blogs()->find($blog->id),
            'subscribed' => $blog->subscribers()->find($user->id),
            'posts' => $blog->posts()->latest()->paginate(5),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Blog $blog)
    {
        return view('blogs.edit', [
            'blog' => $blog->load([
                'comments.user',
                'comments.commentable',
            ]),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateBlogRequest  $request
     * @param  Blog  $blog
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateBlogRequest $request, Blog $blog)
    {
        $blog->update(
            $request->only(['name', 'display_name'])
        );

        return to_route('dashboard.blogs');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blog $blog)
    {
        $blog->delete();

        return to_route('dashboard.blogs');
    }
}
