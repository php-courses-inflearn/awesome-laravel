@extends('layouts.app')

@section('title', $blog->display_name)

@section('content')
    <h3>{{ $blog->display_name }}</h3>
    @auth
        <ul>
            @can(['update', 'delete'], $blog)
                <li><a href="{{ route('blogs.edit', $blog->name) }}">블로그 관리</a></li>
            @endcan
        </ul>
    @endauth

    @include('blogs.show.subscription')
    {{-- 구독, 카테고리, 글 --}}
@endsection
