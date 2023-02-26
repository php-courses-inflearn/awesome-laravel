<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Locale
{
    /**
     * @var array<string>
     */
    private array $locales = ['ko', 'en'];

    private string $key = 'locale';

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     */
    public function handle(Request $request, Closure $next): Response
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
     */
    private function locale(Request $request): ?string
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
