@extends('layouts.app')

@section('title', '글수정')

@section('content')
    <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <input type="text" name="title" value="{{ old('title', $post->title) }}" required autofocus>
        <textarea name="content" required>{{ old('content', $post->content) }}</textarea>
        <input type="file" name="attachments[]" multiple>

        <button type="submit">글수정</button>
    </form>

    <ul>
        @foreach ($post->attachments as $attachment)
            <li>
                <a href="{{ $attachment->link->path }}" download="{{ $attachment->original_name }}">
                    {{ $attachment->original_name }}
                </a>

                @can('delete', $attachment)
                    <form action="{{ route('attachments.destroy', $attachment) }}" method="POST">
                        @csrf
                        @method('DELETE')

                        <button type="submit">삭제</button>
                    </form>
                @endcan
            </li>
        @endforeach
    </ul>
@endsection
