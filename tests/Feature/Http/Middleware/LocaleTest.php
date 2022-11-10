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

    /**
     * 로케일 미들웨어 테스트
     *
     * @return void
     */
    public function testLocale()
    {
        $this->assertTrue(app()->isLocale('ko'));

        $localeMiddleware = app(Locale::class);

        $request = app(Request::class);
        $request->setLaravelSession(app(Session::class));
        $request->header('Accept-Language', 'en');

        $localeMiddleware->handle($request, function () {
        });

        $this->assertTrue(app()->isLocale('en'));
    }

    /**
     * 로케일 미들웨어 테스트 (Query)
     *
     * @return void
     */
    public function testLocaleByQuery()
    {
        $this->assertTrue(app()->isLocale('ko'));

        $localeMiddleware = app(Locale::class);

        $request = app(Request::class);
        $request->setLaravelSession(app(Session::class));

        $request->merge([
            'lang' => 'en',
        ]);

        $localeMiddleware->handle($request, function () {
        });

        $this->assertTrue(app()->isLocale('en'));
    }
}
