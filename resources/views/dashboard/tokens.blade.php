@extends('layouts.app')

@section('title', '토큰')

@section('content')
    @include('dashboard.menu')

    <a href="{{ route('tokens.create') }}">새로운 토큰 만들기</a>

    <ul>
        @foreach ($tokens as $token)
            <li>
                <strong>{{ $token->name }}</strong>

                @foreach ($token->abilities as $ability)
                    <span>{{ $ability }}</span>
                @endforeach

                <form action="{{ route('tokens.destroy', $token) }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="submit">토큰 삭제</button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection
