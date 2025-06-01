<?php

declare(strict_types=1);

namespace App\Kernel\Http;

use App\Kernel\Http\Middleware\CORSMiddleware;
use App\Kernel\Http\Middleware\DispatchMiddleware;
use App\Kernel\Http\Middleware\MatchRouteMiddleware;
use App\Kernel\Http\Middleware\OnThrowableMiddleware;
use App\Kernel\Http\Middleware\ParseBodyMiddleware;
use Component\Http\Kernel as BaseKernel;

final class Kernel extends BaseKernel
{
    public function getMiddleware(): array
    {
        return [
            CORSMiddleware::class,
            //OnThrowableMiddleware::class,
            ParseBodyMiddleware::class,
            MatchRouteMiddleware::class,
            DispatchMiddleware::class,
        ];
    }
}
