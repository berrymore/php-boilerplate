<?php

declare(strict_types=1);

namespace Component\Http\Dispatcher;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface ResponseFactoryInterface
{
    /**
     * @param  mixed                                      $data
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(mixed $data, Request $request): Response;
}
