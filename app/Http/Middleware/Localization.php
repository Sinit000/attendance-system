<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class Localization
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
        // //Simply set language from session
         if(Session::has('locale')){
            App::setlocale(Session::get('locale'));
            session(['locale' => Session::get('locale')]);
            // dd(session());
            Session::save();
        }
        else{
            App::setlocale(Session::get('locale'));
            session(['locale' => Session::get('locale')]);
            // dd(session());
            Session::save();
        }
        App::setlocale(Session::get('locale'));
        return $next($request);
    }
}
