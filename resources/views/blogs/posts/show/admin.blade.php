<ul>
    <li>
        <a href="{{ route('posts.edit', $post->id) }}">수정</a>
    </li>
    <li>
        <form action="{{ route('posts.destroy', $post->id) }}" method="POST">
            @csrf
            @method('DELETE')

            <button type="submit">삭제</button>
        </form>
    </li>
</ul>
