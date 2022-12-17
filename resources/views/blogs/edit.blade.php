@extends('layouts.app')

@section('title', '블로그 관리')

@section('content')
    <div>
        <form action="{{ route('blogs.update', $blog) }}" method="POST">
            @method('PUT')
            @csrf

            <input type="text" name="name" value="{{ $blog->name }}">
            <input type="text" name="display_name" value="{{ $blog->display_name }}">

            <button type="submit">이름 바꾸기</button>
        </form>

        <form action="{{ route('blogs.destroy', $blog) }}" method="POST">
            @method('DELETE')
            @csrf

            <button type="submit">삭제</button>
        </form>
    </div>

    <div>
        <h3>글</h3>

        <ul>
            @foreach($blog->posts as $post)
                <li>
                    <a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
                    <a href="{{ route('posts.edit', $post) }}">수정</a>
                    <form action="{{ route('posts.destroy', $post) }}" method="POST">
                        @csrf
                        @method('DELETE')

                        <button type="submit">삭제</button>
                    </form>
                </li>
            @endforeach
        </ul>
    </div>

    <div>
        <h3>댓글</h3>

        <ul>
            @foreach ($blog->comments as $comment)
                <li>
                    <a href="{{ route('posts.show', $comment->commentable) }}">{{ $comment->commentable->title }}</a>
                    <h4>{{ $comment->user->name }}</h4>
                    <p>{{ $comment->content }}</p>
                    <form action="{{ route('comments.destroy', $comment) }}" method="POST">
                        @csrf
                        @method('DELETE')

                        <button type="submit">삭제</button>
                    </form>
                </li>
            @endforeach
        </ul>
    </div>
@endsection
