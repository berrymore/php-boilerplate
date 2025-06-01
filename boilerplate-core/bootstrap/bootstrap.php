<?php

declare(strict_types=1);

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

    $dotenv = Dotenv::create($envRepository, __DIR__ . '/../');
    $dotenv->load();

    // build container
    $containerBuilder = new ContainerBuilder();

    foreach (include __DIR__ . '/services.php' as $provider) {
        (new $provider())->register($containerBuilder);
    }

    return $containerBuilder->build();
}
