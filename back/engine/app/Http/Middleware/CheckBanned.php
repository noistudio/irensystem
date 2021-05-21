<?php


namespace App\Http\Middleware;

use Fideloper\Proxy\TrustProxies as Middleware;
use Illuminate\Http\Request;
use Closure;

class CheckBanned extends Middleware
{
    public function handle($request, Closure $next)
    {
        if (auth()->check() && auth()->user()->enable == 0) {


            $message = "Еблумба ты забанен отдыхай!";

            return redirect()->route('user_ban')->withMessage($message);
        }

        return $next($request);
    }
}