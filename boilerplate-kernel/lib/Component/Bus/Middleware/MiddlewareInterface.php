<?php

declare(strict_types=1);

namespace Component\Bus\Middleware;

use Component\Bus\EnvelopeInterface;

interface MiddlewareInterface
{
    /**
     * @param  \Component\Bus\EnvelopeInterface          $envelope
     * @param  \Component\Bus\Middleware\StackInterface  $stack
     *
     * @return \Component\Bus\EnvelopeInterface
     */
    public function handle(EnvelopeInterface $envelope, StackInterface $stack): EnvelopeInterface;
}
