@extends('layouts.app')

@section('title', '대시보드')

@section('content')
    <form action="{{ route('dashboard.account') }}" method="POST">
        @csrf
        <input type="text" name="name" value="{{ old('name', $user->name) }}">
        <input type="email" name="email" value="{{ $user->email }}" readonly disabled>
        <input type="password" name="password">
        <input type="password" name="password_confirmation">

        <button type="submit">개인정보 변경하기</button>
    </form>

    @if ($user->provider)
        <input type="text" name="social" id="social" value="{{ $user->provider->name }}">
    @endif
@endsection
