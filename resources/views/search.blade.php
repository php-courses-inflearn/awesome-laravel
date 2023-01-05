@extends('layouts.app')

@section('title', "\"{$query}\" 에 해당하는 검색결과")

@section('content')
    <ul>
        @forelse ($posts as $post)
            <li>
                <a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
            </li>
        @empty
            {{ "\"{$query}\" 에 해당하는 검색결과가 없습니다" }}
        @endforelse
    </ul>
@endsection
