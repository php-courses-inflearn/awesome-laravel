@extends('layouts.app')

@section('title', '새로운 비밀번호 설정')

@section('content')
    <form action="{{ route('password.update') }}" method="POST">
        @csrf
        <input type="email" name="email">
        <input type="password" name="password">
        <input type="password" name="password_confirmation">
        <input type="hidden" name="token" value="{{ $token }}">

        <button type="submit">비밀번호 재설정하기</button>
    </form>
@endsection
