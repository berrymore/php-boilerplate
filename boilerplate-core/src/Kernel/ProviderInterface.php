<?php

declare(strict_types=1);

namespace App\Kernel;

use DI\ContainerBuilder;

interface ProviderInterface
{
    /**
     * @param  \DI\ContainerBuilder<\DI\Container>  $builder
     */
    public function register(ContainerBuilder $builder): void;
}
