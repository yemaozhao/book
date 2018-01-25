<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        
        '/book/public/index.php/service/pay/notify',
        '/book/public/index.php/service/upload/images',
        '/service/upload/images',

    ];
}
