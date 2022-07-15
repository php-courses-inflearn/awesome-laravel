@extends('layouts.app')

@section('title', '블로그 목록')

@section('content')
    <ul>
        @foreach ($blogs as $blog)
            <li>
                <h3><a href="{{ route('blogs.show', $blog->name) }}">{{ $blog->display_name }}</a></h3>
                <div>{{ $blog->user->name }}</div>
            </li>
        @endforeach
    </ul>

    {{ $blogs->links() }}
@endsection


