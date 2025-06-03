<?php

declare(strict_types=1);

namespace App\Kernel\Boot;

use App\Kernel\Lib\Bus\Middleware\CommandHandlerMiddleware;
use App\Kernel\Lib\Bus\Middleware\WrapInTransactionMiddleware;
use App\Kernel\Lib\CommandBus\CommandBus;
use App\Kernel\Lib\CommandBus\CommandBusImpl;
use App\Kernel\Lib\ORM\ORMRegistry;
use Carina\Bus\Bus;
use Carina\Bus\BusImpl;
use Carina\Bus\Middleware\BusRouterMiddleware;
use Carina\Bootloader\Attribute\BeforeBoot;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;

use function DI\autowire;

#[BeforeBoot]
final readonly class BusProvider
{
    public function __invoke(ContainerBuilder $builder): void
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
