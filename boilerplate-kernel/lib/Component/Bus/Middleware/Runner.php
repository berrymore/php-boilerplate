<?php

declare(strict_types=1);

namespace Component\Bus\Middleware;

use Component\Bus\EnvelopeInterface;
use SplQueue;

final class Runner implements MiddlewareInterface, StackInterface
{
    /** @var \SplQueue<\Component\Bus\Middleware\MiddlewareInterface> */
    private SplQueue $queue;

    /**
     * @param  \Component\Bus\Middleware\MiddlewareInterface[]  $pipeline
     */
    public function __construct(array $pipeline)
    {
        $this->queue = new SplQueue();

        foreach ($pipeline as $middleware) {
            $this->queue->enqueue($middleware);
        }

        $this->queue->rewind();
    }

    public function handle(EnvelopeInterface $envelope, StackInterface $stack): EnvelopeInterface
    {
        return $envelope;
    }

    public function next(): MiddlewareInterface
    {
        if ($this->queue->isEmpty()) {
            return $this;
        }

        return $this->queue->dequeue();
    }
}
