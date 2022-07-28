<ul>
    @foreach ($post->attachments as $attachment)
        <li>
            <a href="{{ $attachment->link->path }}" download="{{ $attachment->original_name }}">
                {{ $attachment->original_name }}
            </a>

            @can('delete', $attachment)
                <form action="{{ route('attachments.destroy', $attachment->id) }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="submit">삭제</button>
                </form>
            @endcan
        </li>
    @endforeach
</ul>
