<?php

declare(strict_types=1);

namespace Carina\Bus\Middleware;

use Carina\Bus\EnvelopeInterface;

interface MiddlewareInterface
{
    /**
     * @param  \Carina\Bus\EnvelopeInterface          $envelope
     * @param  \Carina\Bus\Middleware\StackInterface  $stack
     *
     * @return \Carina\Bus\EnvelopeInterface
     */
    public function handle(EnvelopeInterface $envelope, StackInterface $stack): EnvelopeInterface;
}
