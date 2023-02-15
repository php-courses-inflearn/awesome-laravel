<?php

namespace Tests\Feature\Providers;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class HttpClientServiceProviderTest extends TestCase
{
    public function testApiMacro(): void
    {
        Http::fake();

        Http::api('')->get('/');

        Http::assertSent(function (Request $request) {
            return $request->url() === config('app.url').'/api'.'/'
                && $request->hasHeader('Authorization')
                && $request->isJson();
        });
    }
}
