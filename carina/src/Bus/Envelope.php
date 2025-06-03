<?php

declare(strict_types=1);

namespace Carina\Bus;

final class Envelope implements EnvelopeInterface
{
    /** @var object */
    private object $message;

    /** @var object[][] */
    private array $stamps;

    /**
     * @param  object    $message
     * @param  object[]  $stamps
     */
    public function __construct(object $message, array $stamps = [])
    {
        $this->message = $message;
        $this->stamps  = [];

        foreach ($stamps as $stamp) {
            $this->stamps[get_class($stamp)][] = $stamp;
        }
    }

    public function with(object ...$stamps): EnvelopeInterface
    {
        $envelope = clone $this;

        foreach ($stamps as $stamp) {
            $envelope->stamps[get_class($stamp)][] = $stamp;
        }

        return $envelope;
    }

    public function has(string $stamp): bool
    {
        return isset($this->stamps[$stamp]);
    }

    public function last(string $stamp): ?object
    {
        if ($this->has($stamp)) {
            return end($this->stamps[$stamp]) ?: null;
        }

        return null;
    }

    public function all(string $stamp): array
    {
        return $this->stamps[$stamp] ?? [];
    }

    public function getMessage(): object
    {
        return $this->message;
    }
}
