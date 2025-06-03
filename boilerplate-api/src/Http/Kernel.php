<?php

declare(strict_types=1);

namespace App\Api\Http;

use App\Api\Http\Middleware\CORSMiddleware;
use App\Api\Http\Middleware\DispatchMiddleware;
use App\Api\Http\Middleware\MatchRouteMiddleware;
use App\Api\Http\Middleware\ParseBodyMiddleware;
use Carina\Http\Kernel as BaseKernel;

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
