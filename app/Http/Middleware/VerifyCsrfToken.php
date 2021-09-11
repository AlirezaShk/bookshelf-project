<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     * Internal API requests are not checked by CSRF security.
     * 
     * @var array
     */
    protected $except = [
        'api/*',
    ];
}
