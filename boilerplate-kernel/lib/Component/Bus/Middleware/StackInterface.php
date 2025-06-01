<?php

declare(strict_types=1);

namespace Component\Bus\Middleware;

interface StackInterface
{
    /**
     * @return \Component\Bus\Middleware\MiddlewareInterface
     */
    public function next(): MiddlewareInterface;
}
