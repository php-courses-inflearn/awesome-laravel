@extends('layouts.app')

@section('title', '댓글 관리')

@section('content')
    @include('dashboard.menu')

    <ul>
        @foreach ($comments as $comment)
            <ul>
                <li>
                    <a href="{{ route('posts.show', $comment->commentable->id) }}">{{ $comment->commentable->title }}</a>
                    <p>{{ $comment->content }}</p>
                    <form action="{{ route('comments.destroy', $comment->id) }}" method="POST">
                        @csrf
                        @method('DELETE')

                        <button type="submit">삭제</button>
                    </form>
                </li>
            </ul>
        @endforeach
    </ul>
@endsection
