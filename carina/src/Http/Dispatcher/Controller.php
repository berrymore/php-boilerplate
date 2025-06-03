<?php

declare(strict_types=1);

namespace Carina\Http\Dispatcher;

use ReflectionClass;
use ReflectionFunction;

final class Controller implements ControllerInterface
{
    /** @var callable */
    private $callable;

    /** @var \ReflectionFunction */
    private ReflectionFunction $reflectionFunction;

    /** @var \ReflectionClass|null */
    private ?ReflectionClass $reflectionClass;

    /**
     * @param  callable  $callable
     *
     * @throws \ReflectionException
     */
    public function __construct(callable $callable)
    {
        $this->callable           = $callable;
        $this->reflectionFunction = new ReflectionFunction($callable(...));
        $this->reflectionClass    = $this->reflectionFunction->getClosureScopeClass();
    }

    public function getCallable(): callable
    {
        return $this->callable;
    }

    public function getFunctionName(): string
    {
        return $this->reflectionFunction->getName();
    }

    public function getReflectionFunction(): ReflectionFunction
    {
        return $this->reflectionFunction;
    }

    public function getReflectionClass(): ?ReflectionClass
    {
        return $this->reflectionClass;
    }

    public function hasClassScope(): bool
    {
        return $this->reflectionClass !== null;
    }
}
