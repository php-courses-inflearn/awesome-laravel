<h3>글</h3>

<ul>
    @foreach ($blog->posts as $post)
        <li>
            <a href="{{ route('posts.show', $post->id) }}">{{ $post->title }}</a>
            <a href="{{ route('posts.edit', $post->id) }}">수정</a>
            <form action="{{ route('posts.destroy', $post->id) }}" method="POST">
                @csrf
                @method('DELETE')

                <button type="submit">삭제</button>
            </form>
        </li>
    @endforeach
</ul>
