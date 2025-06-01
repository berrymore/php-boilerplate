<?php

declare(strict_types=1);

namespace Component\Http;

use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

abstract class Kernel implements KernelInterface
{
    public function __construct(private readonly ContainerInterface $container)
    {
    }

    /**
     * @return \Component\Http\MiddlewareInterface[]|string[]
     */
    abstract public function getMiddleware(): array;

    public function handle(Request $request): Response
    {
        try {
            return (new Runner($this->container, $this->getMiddleware()))->handle($request);
        } catch (Throwable $e) {
            return $this->onThrowable($e);
        }
    }

    /**
     * @param  \Throwable  $e
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function onThrowable(Throwable $e): Response
    {
        throw $e;
    }
}
