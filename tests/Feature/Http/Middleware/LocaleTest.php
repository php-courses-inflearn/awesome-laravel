<?php

namespace Tests\Feature\Http\Middleware;

use App\Http\Middleware\Locale;
use Illuminate\Contracts\Session\Session;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class LocaleTest extends TestCase
{
    use RefreshDatabase;

    public function testLocaleChangeWithAcceptLanguageHeader(): void
    {
        $this->assertTrue(app()->isLocale('ko'));

        /** @var \App\Http\Middleware\Locale $localeMiddleware */
        $localeMiddleware = app(Locale::class);

        $request = app(Request::class);
        $request->setLaravelSession(app(Session::class));
        $request->header('Accept-Language', 'en');

        $localeMiddleware->handle($request, function () {
            $this->assertTrue(app()->isLocale('en'));
        });
    }

    public function testLocaleChangeWithLangQueryString(): void
    {
        $this->assertTrue(app()->isLocale('ko'));

        /** @var \App\Http\Middleware\Locale $localeMiddleware */
        $localeMiddleware = app(Locale::class);

        $request = app(Request::class);
        $request->setLaravelSession(app(Session::class));

        $request->merge([
            'lang' => 'en',
        ]);

        $localeMiddleware->handle($request, function () {
            $this->assertTrue(app()->isLocale('en'));
        });
    }
}
