<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;
use Override;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateMiddleware extends Authenticate
{
    public function redirectTo(Request $request): string
    {
        return route('view.login');
    }
}
