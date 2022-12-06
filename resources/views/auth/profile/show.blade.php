@extends('layouts.app')

@section('title', '마이페이지')

@section('content')
    <form action="{{ route('profile.edit') }}" method="GET">
        <input type="text" name="name" value="{{ old('name', $user->name) }}" readonly disabled>
        <input type="email" name="email" value="{{ $user->email }}" readonly disabled>

        <button type="submit">개인정보 변경하기</button>
    </form>
@endsection
