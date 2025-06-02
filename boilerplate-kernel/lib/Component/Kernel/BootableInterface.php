<?php

declare(strict_types=1);

namespace Component\Kernel;

use Psr\Container\ContainerInterface;

interface BootableInterface
{
    /**
     * @param  \Psr\Container\ContainerInterface  $container
     */
    public function boot(ContainerInterface $container): void;
}
