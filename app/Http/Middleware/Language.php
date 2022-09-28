<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class Language
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // if user login and has chooose language before and language have in config
        if(session()->has("applocale") AND  array_key_exists(session()->get('applocale'),config('languages'))){
            App::setLocale(session()->get("applocale"));
        }else{
            App::setLocale(config('app.fallback_locale'));
        }
        // if(session()->has("lang_code")){
        //     App::setLocale(session()->get("lang_code"));
        // }
        return $next($request);
    }
}
