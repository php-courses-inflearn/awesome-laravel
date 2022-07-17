@extends('layouts.app')

@section('title', $blog->display_name)

@section('content')
    <h3>{{ $blog->display_name }}</h3>
    @include('blogs.show.admin')
    @include('blogs.show.subscription')

    @include('blogs.show.posts')
@endsection
