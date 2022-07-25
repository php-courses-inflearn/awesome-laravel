@extends('layouts.app')

@section('title', '새로운 토큰 만들기')

@section('content')
    <form action="{{ route('tokens.store') }}" method="POST">
        @csrf

        <input type="text" name="name">

        @foreach ($abilities as $ability)
            <label for="{{ $ability->name }}">{{ $ability->value }}</label>
            <input type="checkbox" name="abilities[]" id="{{ $ability->name }}" value="{{ $ability->value }}">
        @endforeach

        <button type="submit">토큰 만들기</button>
    </form>
@endsection
