@extends('layouts.app')

@section('title', '회원가입')

@section('content')
    <form action="{{ route('register') }}" method="POST">
        @csrf
        <input type="text" name="name" value="{{ old('name') }}">
        <input type="text" name="email" value="{{ old('email') }}">
        <input type="password" name="password">

        <button type="submit">회원가입</button>
    </form>

    @each('auth.social', $providers, 'provider')
@endsection
