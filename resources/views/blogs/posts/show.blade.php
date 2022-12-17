@extends('layouts.app')

@section('title', $post->title)

@section('content')
    <header>
        <h1>{{ $post->title }}</h1>

        @can(['update', 'delete'], $post)
            <ul>
                <li>
                    <a href="{{ route('posts.edit', $post) }}">수정</a>
                </li>
                <li>
                    <form action="{{ route('posts.destroy', $post) }}" method="POST">
                        @csrf
                        @method('DELETE')

                        <button type="submit">삭제</button>
                    </form>
                </li>
            </ul>
        @endcan
    </header>

    <article>{{ $post->content }}</article>

    <ul>
        @foreach ($post->attachments as $attachment)
            <li>
                <a href="{{ $attachment->link->path }}" download="{{ $attachment->original_name }}">
                    {{ $attachment->original_name }}
                </a>
            </li>
        @endforeach
    </ul>

    <div>
        <form action="{{ route('posts.comments.store', $post) }}" method="POST">
            @csrf

            <textarea name="content">{{ old('content') }}</textarea>

            <button type="submit">댓글쓰기</button>
        </form>

        <h3>{{ $post->comments_count . "개의 댓글이 있습니다." }}</h3>

        <ul>
            @foreach($comments as $comment)
                <li>
                    <ul>
                        @include('blogs.posts.show.comments.item')

                        <li>
                            @unless($comment->trashed())
                                <form action="{{ route('posts.comments.store', $comment->commentable) }}" method="POST">
                                    @csrf

                                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                    <textarea name="content">{{ old('content') }}</textarea>

                                    <button type="submit">답글</button>
                                </form>
                            @endunless
                        </li>

                        <li>
                            <ul>
                                @each('blogs.posts.show.comments.item', $comment->replies, 'comment')
                            </ul>
                        </li>
                    </ul>
                </li>
            @endforeach
        </ul>
    </div>
@endsection
