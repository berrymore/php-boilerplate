<?php

declare(strict_types=1);

namespace Component\Bus;

interface EnvelopeInterface
{
    /**
     * @param  object  ...$stamps
     *
     * @return \Component\Bus\EnvelopeInterface
     */
    public function with(object ...$stamps): EnvelopeInterface;

    /**
     * @param  string  $stamp
     *
     * @return bool
     */
    public function has(string $stamp): bool;

    /**
     * @param  string  $stamp
     *
     * @return object|null
     */
    public function last(string $stamp): ?object;

    /**
     * @param  string  $stamp
     *
     * @return object[]
     */
    public function all(string $stamp): array;

    /**
     * @return object
     */
    public function getMessage(): object;
}
