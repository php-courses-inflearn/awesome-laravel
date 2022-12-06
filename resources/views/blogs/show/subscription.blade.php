@unless ($owned)
    @unless ($subscribed)
        <form action="{{ route('subscribe') }}" method="POST">
            @csrf
            <input type="hidden" name="blog_id" value="{{ $blog->id }}">

            <button type="submit">구독</button>
        </form>
    @else
        <form action="{{ route('unsubscribe') }}" method="POST">
            @csrf
            <input type="hidden" name="blog_id" value="{{ $blog->id }}">

            <button type="submit">구독취소</button>
        </form>
    @endunless
@endunless
