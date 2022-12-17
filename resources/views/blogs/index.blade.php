@extends('layouts.app')

@section('title', '블로그 목록')

@section('content')
    <ul>
        @foreach ($blogs as $blog)
            <h3><a href="{{ route('blogs.show', $blog) }}">{{ $blog->display_name }}</a></h3>
            <div>{{ $blog->user->name }}</div>
        @endforeach
    </ul>

    {{ $blogs->links() }}
@endsection
