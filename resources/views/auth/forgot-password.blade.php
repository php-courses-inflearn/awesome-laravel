@extends('layouts.app')

@section('title', '비밀번호 재설정')

@section('content')
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <input type="email" name="email">

        <button type="submit">비밀번호 재설정 이메일 보내기</button>
    </form>
@endsection
