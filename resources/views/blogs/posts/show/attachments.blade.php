<ul>
    @foreach ($post->attachments as $attachment)
        <li>
            <a href="{{ $attachment->link }}" download="{{ $attachment->original_name }}">
                {{ $attachment->original_name }}
            </a>
        </li>
    @endforeach
</ul>
