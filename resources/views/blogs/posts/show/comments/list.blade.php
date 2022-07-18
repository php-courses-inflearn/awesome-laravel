<h3>{{ $post->comments_count . "개의 댓글이 있습니다." }}</h3>

<ul>
    @foreach ($comments as $comment)
        <li>
            @include('blogs.posts.show.comments.list.comment')

            @unless($comment->trashed())
                <form action="{{ route('posts.comments.store', $post->id) }}" method="POST">
                    @csrf

                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                    <textarea name="content">{{ old('content') }}</textarea>

                    <button type="submit">답글</button>
                </form>
            @endunless

            <ul>
                @foreach ($comment->replies as $reply)
                    <li>
                        @include('blogs.posts.show.comments.list.comment', [
                            'comment' => $reply
                        ])
                    </li>
                @endforeach
            </ul>
        </li>
    @endforeach
</ul>
