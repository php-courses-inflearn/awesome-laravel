@extends('layouts.app')

@section('title', '글수정')

@section('content')
    <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <input type="text" name="title" value="{{ old('title', $post->title) }}" required autofocus>
        <textarea name="content" required>{{ old('content', $post->content) }}</textarea>
        <input type="file" name="attachments[]" multiple>

        <button type="submit">글수정</button>
    </form>

    @include('blogs.posts.edit.attachments')
@endsection
