<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Log;
class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$role): Response
    {
        Log::debug(json_encode($request,true));
        if (!in_array($request->user()->role, $role)) {

            if ($request->user()->role == 1 || $request->user()->role == 2) {
                return redirect()->route('dashboard');
            }else {
                return redirect()->route('home');
            }
        }
        return $next($request);
    }
}
