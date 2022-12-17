@extends('layouts.app')

@section('title', '글쓰기')

@section('content')
    <form action="{{ route('blogs.posts.store', $blog) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <input type="text" name="title" value="{{ old('title') }}" required autofocus>
        <textarea name="content" required>{{ old('content') }}</textarea>
        <input type="file" name="attachments[]" multiple>

        <button type="submit">글쓰기</button>
    </form>
@endsection
