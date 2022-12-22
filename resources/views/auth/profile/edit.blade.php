@extends('layouts.app')

@section('title', '마이페이지 - 개인정보수정')

@section('content')
    <form action="{{ route('profile.update') }}" method="POST">
        @method('PUT')
        @csrf
        <input type="text" name="name" value="{{ old('name', $user->name) }}">
        <input type="email" name="email" value="{{ $user->email }}" readonly disabled>

        @if(session()->socialiteMissingAll())
            <input type="password" name="password">
            <input type="password" name="password_confirmation">
        @endif

        <button type="submit">개인정보 변경하기</button>
    </form>
@endsection
