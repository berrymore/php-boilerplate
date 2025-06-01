<?php

declare(strict_types=1);

namespace App\Kernel\Providers;

use App\Kernel\Lib\Bus\Middleware\CommandHandlerMiddleware;
use App\Kernel\Lib\Bus\Middleware\WrapInTransactionMiddleware;
use App\Kernel\Lib\CommandBus\CommandBus;
use App\Kernel\Lib\CommandBus\CommandBusImpl;
use App\Kernel\Lib\ORM\ORMRegistry;
use App\Kernel\ProviderInterface;
use Component\Bus\Bus;
use Component\Bus\BusImpl;
use Component\Bus\Middleware\BusRouterMiddleware;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;

use function DI\autowire;

final readonly class BusProvider implements ProviderInterface
{
    public function register(ContainerBuilder $builder): void
    {
        $builder->addDefinitions(
            [
                'bus.command_bus' => static function (ContainerInterface $container): Bus {
                    return new BusImpl(
                        [
                            new WrapInTransactionMiddleware($container->get(ORMRegistry::class)),
                            new CommandHandlerMiddleware($container)
                        ]
                    );
                },
                Bus::class        => static function (ContainerInterface $container): Bus {
                    return new BusImpl(
                        [
                            new BusRouterMiddleware(
                                [
                                    'command_bus' => $container->get('bus.command_bus'),
                                ]
                            )
                        ]
                    );
                },
                CommandBus::class => autowire(CommandBusImpl::class),
            ]
        );
    }
}
