<?php

declare(strict_types=1);

namespace Carina\Http\Dispatcher;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;

class ControllerResolver implements ControllerResolverInterface
{
    /**
     * @throws \ReflectionException
     */
    public function resolve(Request $request): ControllerInterface
    {
        if ($handler = $request->attributes->get('_handler')) {
            if (! is_callable($handler)) {
                throw new InvalidArgumentException('Request attribute "_handler" should contain a callable');
            }

            return new Controller($handler);
        }

        throw new InvalidArgumentException('Request has no "_handler" attribute');
    }
}
