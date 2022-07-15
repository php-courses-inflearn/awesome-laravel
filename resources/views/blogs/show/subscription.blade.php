@unless ($owned)
    @unless ($subscribed)
        <form action="{{ route('subscribe', $blog->name) }}" method="POST">
            @csrf

            <button type="submit">구독</button>
        </form>
    @else
        <form action="{{ route('unsubscribe', $blog->name) }}" method="POST">
            @csrf
            @method('DELETE')

            <button type="submit">구독취소</button>
        </form>
    @endunless
@endunless
