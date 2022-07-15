@extends('layouts.app')

@section('title', '내 구독자')

@section('content')
    @include('dashboard.menu')

    <ul>
        @foreach ($blogs as $blog)
            <li>
                <h4>{{ $blog->name }}</h4>

                <ul>
                    @foreach ($blog->subscribers as $user)
                        <li>{{ $user->name }}</li>
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ul>
@endsection
