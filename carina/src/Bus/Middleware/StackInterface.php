<?php

declare(strict_types=1);

namespace Carina\Bus\Middleware;

interface StackInterface
{
    /**
     * @return \Carina\Bus\Middleware\MiddlewareInterface
     */
    public function next(): MiddlewareInterface;
}
