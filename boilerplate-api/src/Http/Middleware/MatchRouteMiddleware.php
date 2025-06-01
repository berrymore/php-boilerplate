<?php

declare(strict_types=1);

namespace App\Api\Http\Middleware;

use Component\Http\MiddlewareInterface;
use Component\Http\RequestHandlerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Router;

final readonly class MatchRouteMiddleware implements MiddlewareInterface
{
    public function __construct(private ContainerInterface $container, private Router $router)
    {
    }

    public function process(Request $request, RequestHandlerInterface $requestHandler): Response
    {
        try {
            $this->router->setContext((new RequestContext())->fromRequest($request));

            $match = $this->router->matchRequest($request);
        } catch (ResourceNotFoundException) {
            return new Response('Not found', Response::HTTP_NOT_FOUND);
        } catch (MethodNotAllowedException) {
            return new Response('Method not allowed', Response::HTTP_METHOD_NOT_ALLOWED);
        }

        $request->attributes->set('_handler', [$this->container->get($match['_service']), $match['_method']]);

        return $requestHandler->handle($request);
    }
}
