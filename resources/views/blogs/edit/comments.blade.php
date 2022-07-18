<h3>댓글</h3>

<ul>
    @foreach ($blog->comments as $comment)
        <ul>
            <li>
                <a href="{{ route('posts.show', $comment->commentable->id) }}">{{ $comment->commentable->title }}</a>
                <h4>{{ $comment->user->name }}</h4>
                <p>{{ $comment->content }}</p>
                <form action="{{ route('comments.destroy', $comment->id) }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="submit">삭제</button>
                </form>
            </li>
        </ul>
    @endforeach
</ul>
