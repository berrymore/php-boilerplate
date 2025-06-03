<?php

declare(strict_types=1);

namespace Carina\Bus;

interface Bus
{
    /**
     * @param  object    $message
     * @param  object[]  $stamps
     *
     * @return \Carina\Bus\EnvelopeInterface
     */
    public function dispatch(object $message, array $stamps = []): EnvelopeInterface;
}
