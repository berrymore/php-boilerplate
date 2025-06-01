<?php

declare(strict_types=1);

namespace Component\Bus;

use Component\Bus\Middleware\Runner;

class BusImpl implements Bus
{
    /** @var \Component\Bus\Middleware\MiddlewareInterface[] */
    private array $pipeline;

    /**
     * @param  \Component\Bus\Middleware\MiddlewareInterface[]  $pipeline
     */
    public function __construct(array $pipeline)
    {
        $this->pipeline = $pipeline;
    }

    public function dispatch(object $message, array $stamps = []): EnvelopeInterface
    {
        if (! $message instanceof EnvelopeInterface) {
            $message = new Envelope($message);
        }

        $message = $message->with(...$stamps);

        $runner = new Runner($this->pipeline);

        return $runner->next()->handle($message, $runner);
    }
}
