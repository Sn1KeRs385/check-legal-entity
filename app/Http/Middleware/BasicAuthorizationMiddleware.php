<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class BasicAuthorizationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var string $login */
        $login = Config::get('auth.basic.login');

        /** @var string $password */
        $password = Config::get('auth.basic.password');

        if ($request->getUser() === $login && $request->getPassword() === $password) {
            return $next($request);
        }

        throw new UnauthorizedHttpException('Basic', __('auth.failed'));
    }
}
