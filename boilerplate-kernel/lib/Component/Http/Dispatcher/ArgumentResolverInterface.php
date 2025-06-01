<?php

declare(strict_types=1);

namespace Component\Http\Dispatcher;

use Symfony\Component\HttpFoundation\Request;

interface ArgumentResolverInterface
{
    /**
     * @param  \Symfony\Component\HttpFoundation\Request       $request
     * @param  \Component\Http\Dispatcher\ControllerInterface  $controller
     *
     * @return array
     */
    public function resolve(Request $request, ControllerInterface $controller): array;
}
