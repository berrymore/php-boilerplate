<?php

declare(strict_types=1);

namespace Carina\Http;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface RequestHandlerInterface
{
    /**
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request): Response;
}
