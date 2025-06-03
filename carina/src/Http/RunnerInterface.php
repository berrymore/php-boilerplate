<?php

declare(strict_types=1);

namespace Carina\Http;

interface RunnerInterface extends MiddlewareInterface, RequestHandlerInterface
{
    /**
     * @param  \Carina\Http\MiddlewareInterface  $middleware
     */
    public function push(MiddlewareInterface $middleware): void;
}
