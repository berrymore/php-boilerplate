<?php

declare(strict_types=1);

namespace Component\Http;

interface RunnerInterface extends MiddlewareInterface, RequestHandlerInterface
{
    /**
     * @param  \Component\Http\MiddlewareInterface  $middleware
     */
    public function push(MiddlewareInterface $middleware): void;
}
