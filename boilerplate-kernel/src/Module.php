<?php

declare(strict_types=1);

namespace App\Kernel;

use App\Kernel\Lib\Config\ConfigConfigurator;
use App\Kernel\Providers\BusProvider;
use App\Kernel\Providers\ConfigProvider;
use App\Kernel\Providers\ORMProvider;
use App\Kernel\Providers\ValidatorProvider;
use Component\Kernel\BootableInterface;
use Component\Kernel\ModuleInterface;
use Psr\Container\ContainerInterface;

final readonly class Module implements ModuleInterface, BootableInterface
{
    public function getProviders(): array
    {
        return [
            ConfigProvider::class,
            BusProvider::class,
            ValidatorProvider::class,
            ORMProvider::class,
        ];
    }

    public function boot(ContainerInterface $container): void
    {
        $container->get(ConfigConfigurator::class)->addPath(__DIR__ . '/../resources/config');
    }
}
