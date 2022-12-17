@extends('layouts.app')

@section('title', $blog->display_name)

@section('content')
    <header>
        <h3>{{ $blog->display_name }}</h3>

        @auth
            <ul>
                @can(['update', 'delete'], $blog)
                    <li><a href="{{ route('blogs.edit', $blog) }}">블로그 관리</a></li>
                @endcan

                @can('create', [\App\Models\Post::class, $blog])
                    <li><a href="{{ route('blogs.posts.create', $blog) }}">글쓰기</a></li>
                @endcan
            </ul>
        @endauth

        @unless ($owned)
            @unless ($subscribed)
                <form action="{{ route('subscribe') }}" method="POST">
                    @csrf
                    <input type="hidden" name="blog_id" value="{{ $blog->id }}">

                    <button type="submit">구독</button>
                </form>
            @else
                <form action="{{ route('unsubscribe') }}" method="POST">
                    @csrf
                    <input type="hidden" name="blog_id" value="{{ $blog->id }}">

                    <button type="submit">구독취소</button>
                </form>
            @endunless
        @endunless
    </header>

    <ul>
        @foreach($posts as $post)
            <li>
                <a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
            </li>
        @endforeach
    </ul>

    {{ $posts->links() }}
@endsection
