<?php

declare(strict_types=1);

use Carina\Bootloader\BootContext;
use Carina\Bootloader\Attribute\OnBoot;
use Carina\Bootloader\Attribute\BeforeBoot;
use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Dotenv\Repository\Adapter\EnvConstAdapter;
use Dotenv\Repository\RepositoryBuilder;
use Psr\Container\ContainerInterface;

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * @throws \Exception
 */
function boot(): ContainerInterface
{
    // load .env
    $envRepository = RepositoryBuilder::createWithNoAdapters()
        ->addAdapter(EnvConstAdapter::class)
        ->immutable()
        ->make();

    $dotenv = Dotenv::create($envRepository, __DIR__);
    $dotenv->load();

    // build container
    $containerBuilder = new ContainerBuilder();
    $composer         = json_decode(file_get_contents(__DIR__ . '/../composer.json'), true);

    $context = new BootContext(
        array_map(fn(string $file) => __DIR__ . '/../' . $file, $composer['extra']['bootload'] ?? []),
        [BeforeBoot::class, OnBoot::class]
    );

    $context->refresh();

    foreach ($context->getAttributedBy(BeforeBoot::class) as $refl) {
        if ($refl instanceof ReflectionClass) {
            $instance = $refl->newInstanceWithoutConstructor();

            if (is_callable($instance)) {
                $instance($containerBuilder);
            }
        } elseif ($refl instanceof ReflectionMethod) {
            $instance = $refl->getDeclaringClass()->newInstanceWithoutConstructor();

            $refl->invoke($instance, $containerBuilder);
        }
    }

    $container = $containerBuilder->build();

    foreach ($context->getAttributedBy(OnBoot::class) as $refl) {
        if ($refl instanceof ReflectionClass) {
            $instance = $refl->newInstanceWithoutConstructor();

            if (is_callable($instance)) {
                $instance($container);
            }
        } elseif ($refl instanceof ReflectionMethod) {
            $instance = $refl->getDeclaringClass()->newInstanceWithoutConstructor();

            $container->call([$instance, $refl->getName()]);
        }
    }

    return $container;
}
