@foreach ($providers as $provider)
    <a href="{{ route('login.social', $provider->value) }}">{{ $provider->name }}</a>
@endforeach
