<?php

declare(strict_types=1);

namespace Component\Http\Dispatcher;

use Symfony\Component\HttpFoundation\Request;

interface ControllerResolverInterface
{
    /**
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     *
     * @return \Component\Http\Dispatcher\ControllerInterface
     */
    public function resolve(Request $request): ControllerInterface;
}
