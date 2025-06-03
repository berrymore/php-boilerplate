<?php

declare(strict_types=1);

namespace App\Kernel\Lib\Bus\Middleware;

use Carina\Bus\EnvelopeInterface;
use Carina\Bus\Middleware\MiddlewareInterface;
use Carina\Bus\Middleware\StackInterface;
use Psr\Container\ContainerInterface;

final readonly class CommandHandlerMiddleware implements MiddlewareInterface
{
    public function __construct(private ContainerInterface $container)
    {
    }

    public function handle(EnvelopeInterface $envelope, StackInterface $stack): EnvelopeInterface
    {
        $handler = $this->container->get(str_replace('Command', 'Handler', $envelope->getMessage()::class));

        call_user_func($handler, $envelope->getMessage());

        return $envelope;
    }
}
