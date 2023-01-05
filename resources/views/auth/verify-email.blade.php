@extends('layouts.app')

@section('title', '이메일 인증')

@section('content')
    <strong>이메일 인증이 필요합니다.</strong>

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf

        <button type="submit">이메일 재전송</button>
    </form>
@endsection
