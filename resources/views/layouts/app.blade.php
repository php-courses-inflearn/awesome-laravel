<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>라라벨 - @yield('title')</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body>
        <nav>
            <ul>
                <li><a href="{{ url('/') }}">홈</a></li>
                <li>
                    <form action="{{ route('search') }}" method="GET">
                        <input type="search" name="query" placeholder="search...">

                        <button type="submit">검색</button>
                    </form>
                </li>
                @guest
                    <li><a href="{{ route('login') }}">로그인</a></li>
                    <li><a href="{{ route('register') }}">회원가입</a></li>
                @else
                    <li><a href="{{ route('profile.show') }}">마이페이지</a></li>
                    <li><a href="{{ route('dashboard.blogs') }}">대시보드</a></li>
                    <li><a href="{{ route('blogs.index') }}">블로그</a></li>
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
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        @if (session()->has('status'))
            <div>{{ session()->get('status') }}</div>
        @endif

        <main>@yield('content')</main>

        @auth
            <script type="module">
                const id = "{{ auth()->user()->id }}"

                Echo.private(`App.Models.User.${id}`)
                    .notification(n => {
                        switch (n.type) {
                            case 'App\\Notifications\\Subscribed':
                                return console.log(n.user)
                            case 'App\\Notifications\\Published':
                                return console.log(n.post)
                        }
                    })
            </script>
        @endauth
    </body>
</html>
