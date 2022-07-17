@auth
    <ul>
        @can(['update', 'delete'], $blog)
            <li><a href="{{ route('blogs.edit', $blog->name) }}">블로그 관리</a></li>
        @endcan

        @can('create', \App\Models\Post::class)
            <li><a href="{{ route('blogs.posts.create', $blog->name) }}">글쓰기</a></li>
        @endcan
    </ul>
@endauth
