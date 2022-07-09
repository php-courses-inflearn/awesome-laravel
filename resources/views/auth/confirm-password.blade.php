@extends('layouts.app')

@section('title', '비밀번호 확인')

@section('content')
    <form action="{{ route('password.confirm') }}" method="POST">
        @csrf
        <input type="password" name="password">

        <button type="submit">비밀번호 확인하기</button>
    </form>
@endsection
