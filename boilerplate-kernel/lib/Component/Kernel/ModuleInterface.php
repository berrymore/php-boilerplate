<?php

declare(strict_types=1);

namespace Component\Kernel;

interface ModuleInterface
{
    /**
     * @return string[]
     */
    public function getProviders(): array;
}
