<?php

declare(strict_types=1);

namespace Carina\Http\Dispatcher;

use ReflectionClass;
use ReflectionFunction;

interface ControllerInterface
{
    /**
     * @return callable
     */
    public function getCallable(): callable;

    /**
     * @return string
     */
    public function getFunctionName(): string;

    /**
     * @return \ReflectionFunction
     */
    public function getReflectionFunction(): ReflectionFunction;

    /**
     * @return \ReflectionClass|null
     */
    public function getReflectionClass(): ?ReflectionClass;

    /**
     * @return bool
     */
    public function hasClassScope(): bool;
}
