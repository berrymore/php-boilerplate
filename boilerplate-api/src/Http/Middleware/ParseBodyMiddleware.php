<?php

namespace App\Api\Http\Middleware;

use Component\Http\MiddlewareInterface;
use Component\Http\RequestHandlerInterface;
use Symfony\Component\HttpFoundation\Exception\JsonException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final readonly class ParseBodyMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $requestHandler): Response
    {
        if ($request->getContentTypeFormat() === 'json') {
            try {
                $data = $request->toArray();
            } catch (JsonException) {
                $data = [];
            }

            $request->request->replace($data);
        }

        return $requestHandler->handle($request);
    }
}
