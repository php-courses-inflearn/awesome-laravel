<div>
    <h2>라라벨 커뮤니티의 최신글 살펴보기!</h2>

    <ul>
        @foreach ($posts as $post)
            <li>
                <a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
            </li>
        @endforeach
    </ul>
</div>
