<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->query('locale')
            ?? session('locale')
            ?? $request->getPreferredLanguage(['sw', 'en'])
            ?? config('app.locale', 'sw');

        if (in_array($locale, ['sw', 'en'], true)) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
