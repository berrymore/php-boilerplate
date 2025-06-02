<?php

declare(strict_types=1);

namespace App\Welcome;

use App\Kernel\Lib\ORM\ORMConfigurator;
use App\Welcome\Providers\WelcomeProvider;
use Component\Kernel\BootableInterface;
use Component\Kernel\ModuleInterface;
use Psr\Container\ContainerInterface;

final readonly class Module implements ModuleInterface, BootableInterface
{
    public function getProviders(): array
    {
        return [
            WelcomeProvider::class,
        ];
    }

    public function boot(ContainerInterface $container): void
    {
        $container->get(ORMConfigurator::class)->addPath(__DIR__ . '/../resources/entities');
    }
}
