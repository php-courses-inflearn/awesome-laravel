@extends('layouts.app')

@section('title', "\"{$q}\" 에 해당하는 검색결과")

@section('content')
    <ul>
        @forelse ($posts as $post)
            <li>
                <a href="{{ route('posts.show', $post->id) }}">{{ $post->title }}</a>
            </li>
        @empty
            {{ "\"{$q}\" 에 해당하는 검색결과가 없습니다" }}
        @endforelse
    </ul>
@endsection
