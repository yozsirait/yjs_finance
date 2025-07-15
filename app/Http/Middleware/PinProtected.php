<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PinProtected
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('pin_verified')) {
            return redirect()->route('pin.form');
        }

        return $next($request);
    }
}
