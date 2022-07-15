@extends('layouts.app')

@section('title', '대시보드')

@section('content')
    @include('dashboard.menu')

    {{-- 개인정보 수정 --}}
    <form action="{{ route('dashboard.profile') }}" method="POST">
        @method('PUT')
        @csrf
        <input type="text" name="name" value="{{ old('name', $user->name) }}">
        <input type="email" name="email" value="{{ $user->email }}" readonly disabled>

        @unless($user->provider)
            <input type="password" name="password">
            <input type="password" name="password_confirmation">
        @endunless

        <button type="submit">개인정보 변경하기</button>
    </form>

    {{-- 회원탈퇴 --}}
    <form action="{{ route('dashboard.profile') }}" method="POST">
        @method('DELETE')
        @csrf

        <button type="submit">회원탈퇴</button>
    </form>
@endsection
