<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Locale
{
    /**
     * @var array|string[] $locales
     */
    private array $locales = ['ko', 'en'];

    /**
     * @var string $Key
     */
    private string $key = 'locale';

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $locale = $this->locale($request);

        if ($request->session()->missing($this->key)) {
            $request->session()->put($this->key, $locale);
        }

        app()->setLocale($request->session()->get($this->key));

        return $next($request);
    }

    /**
     * 로케일
     *
     * @param Request $request
     * @return mixed|string|null
     */
    private function locale(Request $request)
    {
        $locale = $request->getPreferredLanguage($this->locales);

        if ($request->has('lang') && $lang = $request->lang) {
            if (in_array($lang, $this->locales)) {
                $locale = $lang;
            }
        }

        return $locale;
    }
}
