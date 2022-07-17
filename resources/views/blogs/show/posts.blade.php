<ul>
    @foreach ($posts as $post)
        <li>
            <a href="{{ route('posts.show', $post->id) }}">{{ $post->title }}</a>
        </li>
    @endforeach
</ul>

{{ $posts->links() }}
