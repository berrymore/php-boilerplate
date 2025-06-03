<?php

declare(strict_types=1);

namespace Carina\Http;

use Psr\Container\ContainerInterface;
use SplQueue;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class Runner implements RunnerInterface
{
    /** @var \SplQueue<\Carina\Http\MiddlewareInterface> */
    private SplQueue $pipeline;

    /**
     * @param  \Psr\Container\ContainerInterface            $container
     * @param  \Carina\Http\MiddlewareInterface[]|string[]  $pipeline
     */
    public function __construct(private readonly ContainerInterface $container, array $pipeline = [])
    {
        $this->pipeline = new SplQueue();

        foreach ($pipeline as $middleware) {
            $this->pipeline->enqueue($middleware);
        }
    }

    public function push(MiddlewareInterface $middleware): void
    {
        $this->pipeline->enqueue($middleware);
    }

    public function process(Request $request, RequestHandlerInterface $requestHandler): Response
    {
        return new Response('Not found', Response::HTTP_NOT_FOUND);
    }

    public function handle(Request $request): Response
    {
        if ($this->pipeline->isEmpty()) {
            return $this->process($request, $this);
        }

        /** @var \Carina\Http\MiddlewareInterface|string $middleware */
        $middleware = $this->pipeline->dequeue();

        if (! $middleware instanceof MiddlewareInterface) {
            $middleware = $this->container->get($middleware);
        }

        return $middleware->process($request, $this);
    }
}
