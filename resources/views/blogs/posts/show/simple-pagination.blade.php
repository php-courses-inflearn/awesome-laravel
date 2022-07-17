@if ($prev)
    <a href="{{ route('posts.show', $prev->id) }}">{{ $prev->title }}</a>
@endif

@if ($next)
    <a href="{{ route('posts.show', $next->id) }}">{{ $next->title }}</a>
@endif
