@extends('layouts.app')

@section('title', '블로그 관리')

@section('content')
    @include('dashboard.menu')

    <a href="{{ route('blogs.create') }}">새로운 블로그 만들기</a>

    <ul>
        @foreach ($blogs as $blog)
            <li>
                <a href="{{ route('blogs.show', $blog) }}">{{ $blog->display_name }}</a>
                <a href="{{ route('blogs.edit', $blog) }}">블로그 관리</a>
            </li>
        @endforeach
    </ul>
@endsection
