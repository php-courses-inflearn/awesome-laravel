@extends('layouts.app')

@section('title', '대시보드')

@section('content')
    @include('dashboard.menu')

    <form action="{{ route('user.update') }}" method="POST">
        @method('PUT')
        @csrf
        <input type="text" name="name" value="{{ old('name', $user->name) }}">
        <input type="email" name="email" value="{{ $user->email }}" readonly disabled>

        @if(session()->missing('Socialite'))
            <input type="password" name="password">
            <input type="password" name="password_confirmation">
        @endif

        <button type="submit">개인정보 변경하기</button>
    </form>

    <form action="{{ route('user.destroy') }}" method="POST">
        @method('DELETE')
        @csrf

        <button type="submit">회원탈퇴</button>
    </form>
@endsection
