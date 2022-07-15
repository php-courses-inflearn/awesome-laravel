<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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
            'blogs' => Blog::with('user')->paginate(5)
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:blogs,name|max:255|min:4',
            'display_name' => 'required|max:255'
        ]);

        $request->validate([
            'name' => 'regex:/^[a-zA-Z\d\-_.]*$/'
        ]);

        $user = $request->user();

        $user->blogs()->create(
            $request->only(['name', 'display_name'])
        );

        return to_route('dashboard.blogs');;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function show(Blog $blog)
    {
        return view('blogs.show', [
            'blog' => $blog
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
            'blog' => $blog
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Blog $blog)
    {
        $request->validate([
            'name' => 'required|unique:blogs,name|max:255|min:4',
            'display_name' => 'required|max:255'
        ]);

        $request->validate([
            'name' => 'regex:/^[a-zA-Z\d\-_.]*$/'
        ]);

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


