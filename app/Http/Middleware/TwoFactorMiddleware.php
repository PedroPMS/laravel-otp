<?php

namespace App\Http\Middleware;

use Closure;
use PragmaRX\Google2FALaravel\Middleware;

class TwoFactorMiddleware extends Middleware
{
    public function handle($request, Closure $next)
    {
        if (auth()->user()->google2fa_secret === null) {
            return $next($request);
        }
        return parent::handle($request, $next);
    }
}
