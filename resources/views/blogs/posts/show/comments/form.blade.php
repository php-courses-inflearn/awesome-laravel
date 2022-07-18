<form action="{{ route('posts.comments.store', $post->id) }}" method="POST">
    @csrf

    <textarea name="content">{{ old('content') }}</textarea>

    <button type="submit">댓글쓰기</button>
</form>
