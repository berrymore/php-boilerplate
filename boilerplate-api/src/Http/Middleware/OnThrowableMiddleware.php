<?php

declare(strict_types=1);

namespace App\Api\Http\Middleware;

use Carina\Http\MiddlewareInterface;
use Carina\Http\RequestHandlerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final readonly class OnThrowableMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $requestHandler): Response
    {
        try {
            return $requestHandler->handle($request);
        } catch (Throwable $e) {
            return new JsonResponse(
                ['message' => $e->getMessage(), 'trace' => $e->getTrace()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
