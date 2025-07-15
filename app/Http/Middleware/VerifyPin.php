<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyPin
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('verified_pin')) {
            return redirect()->route('pin.prompt');
        }

        return $next($request);
    }
}
