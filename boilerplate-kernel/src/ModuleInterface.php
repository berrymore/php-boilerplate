<?php

declare(strict_types=1);

namespace App\Kernel;

interface ModuleInterface
{
    /**
     * @return string[]
     */
    public function getProviders(): array;
}
