@extends('layouts.app')

@section('title', '내가 구독한 블로그')

@section('content')
    @include('dashboard.menu')

    <ul>
        @foreach ($blogs as $blog)
            <li><a href="{{ route('blogs.show', $blog) }}">{{ $blog->name }}</a></li>
        @endforeach
    </ul>
@endsection
