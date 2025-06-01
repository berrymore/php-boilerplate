<?php

declare(strict_types=1);

namespace Component\Bus;

interface Bus
{
    /**
     * @param  object    $message
     * @param  object[]  $stamps
     *
     * @return \Component\Bus\EnvelopeInterface
     */
    public function dispatch(object $message, array $stamps = []): EnvelopeInterface;
}
