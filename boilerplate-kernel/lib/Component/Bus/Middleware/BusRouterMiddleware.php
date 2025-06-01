<?php

declare(strict_types=1);

namespace Component\Bus\Middleware;

use Component\Bus\EnvelopeInterface;
use Component\Bus\Stamps\BusNameStamp;
use InvalidArgumentException;
use OutOfRangeException;

final readonly class BusRouterMiddleware implements MiddlewareInterface
{
    /**
     * @param  array<string, \Component\Bus\Bus>  $buses
     */
    public function __construct(private array $buses)
    {
    }

    public function handle(EnvelopeInterface $envelope, StackInterface $stack): EnvelopeInterface
    {
        if ($busNameStamp = $envelope->last(BusNameStamp::class)) {
            /** @var \Component\Bus\Stamps\BusNameStamp $busNameStamp */

            if (! isset($this->buses[$busNameStamp->getName()])) {
                throw new OutOfRangeException(sprintf('Bus "%s" is not defined', $busNameStamp->getName()));
            }

            return $this->buses[$busNameStamp->getName()]->dispatch($envelope);
        }

        throw new InvalidArgumentException('Bus name is not set');
    }
}
