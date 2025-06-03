<?php

declare(strict_types=1);

namespace App\Api\Http\Middleware;

use Carina\Http\MiddlewareInterface;
use Carina\Http\RequestHandlerInterface;
use Noodlehaus\ConfigInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final readonly class CORSMiddleware implements MiddlewareInterface
{
    public function __construct(private ConfigInterface $config)
    {
    }

    public function process(Request $request, RequestHandlerInterface $requestHandler): Response
    {
        $response = $request->getMethod() === Request::METHOD_OPTIONS
            ? new Response()
            : $requestHandler->handle($request);

        $response->headers->set('Access-Control-Allow-Origin', $this->getHeaderValue('allowed_origins'));
        $response->headers->set('Access-Control-Allow-Methods', $this->getHeaderValue('allowed_methods'));
        $response->headers->set('Access-Control-Allow-Headers', $this->getHeaderValue('allowed_headers'));

        return $response;
    }

    /**
     * @param  string  $name
     *
     * @return string
     */
    private function getHeaderValue(string $name): string
    {
        return implode(',', $this->config->get('http.cors.' . $name));
    }
}
