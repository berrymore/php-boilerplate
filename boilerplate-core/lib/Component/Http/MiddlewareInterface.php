<?php

declare(strict_types=1);

namespace Component\Http;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface MiddlewareInterface
{
    /**
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     * @param  \Component\Http\RequestHandlerInterface    $requestHandler
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function process(Request $request, RequestHandlerInterface $requestHandler): Response;
}
