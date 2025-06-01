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

    $dotenv = Dotenv::create($envRepository, __DIR__);
    $dotenv->load();

    // build container
    $containerBuilder = new ContainerBuilder();
    $composer         = json_decode(file_get_contents(__DIR__ . '/../composer.json'), true);

    if (isset($composer['x-modules'])) {
        foreach ($composer['x-modules'] as $moduleClass) {
            /** @var \App\Kernel\ModuleInterface $module */
            $module = new $moduleClass();

            foreach ($module->getProviders() as $provider) {
                new $provider()->register($containerBuilder);
            }
        }
    }

    return $containerBuilder->build();
}
