@extends('layouts.app')

@section('title', '글쓰기')

@section('content')
    <form action="{{ route('blogs.posts.store', $blog->name) }}" method="POST">
        @csrf

        <input type="text" name="title" value="{{ old('title') }}" required autofocus>
        <textarea name="content" required>{{ old('content') }}</textarea>

        <button type="submit">글쓰기</button>
    </form>
@endsection
