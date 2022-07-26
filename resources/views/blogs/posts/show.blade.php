@extends('layouts.app')

@section('title', $post->title)

@section('content')
    <header>
        <h1>{{ $post->title }}</h1>

        @can(['update', 'delete'], $post)
            @include('blogs.posts.show.admin')
        @endcan
    </header>

    <article>{{ $post->content }}</article>

    @include('blogs.posts.show.attachments')

    @include('blogs.posts.show.comments.form')
    @include('blogs.posts.show.comments.list')
@endsection
