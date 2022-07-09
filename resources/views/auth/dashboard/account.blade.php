@extends('layouts.app')

@section('title', '대시보드')

@section('content')
    @include('auth.dashboard.menu')

    <form action="{{ route('dashboard.account') }}" method="POST">
        @csrf
        <input type="text" name="name" value="{{ old('name', $user->name) }}">
        <input type="email" name="email" value="{{ $user->email }}" readonly disabled>

        @unless($user->provider)
            <input type="password" name="password">
            <input type="password" name="password_confirmation">
        @endunless

        <button type="submit">개인정보 변경하기</button>
    </form>
@endsection
