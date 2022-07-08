<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>라라벨 - @yield('title')</title>
    </head>

    <body>
        <nav>
            <ul>
                <li><a href="{{ route('home') }}">홈</a></li>
                @guest
                    <li><a href="{{ route('login') }}">로그인</a></li>
                    <li><a href="{{ route('register') }}">회원가입</a></li>
                @else
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit">로그아웃</button>
                        </form>
                    </li>
                @endguest
            </ul>
        </nav>

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <ul>
                    <li>{{ $error }}</li>
                </ul>
            @endforeach
        @endif

        @if (session()->has('status'))
            <div>{{ session()->get('status') }}</div>
        @endif

        <main>@yield('content')</main>
    </body>
</html>
