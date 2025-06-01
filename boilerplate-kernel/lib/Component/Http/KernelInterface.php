<?php

declare(strict_types=1);

namespace Component\Http;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface KernelInterface
{
    /**
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request): Response;
}
