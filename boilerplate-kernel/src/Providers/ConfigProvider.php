<?php

declare(strict_types=1);

namespace App\Kernel\Providers;

use App\Kernel\Lib\Config\ConfigConfigurator;
use Component\Kernel\ProviderInterface;
use DI\ContainerBuilder;
use Noodlehaus\Config;
use Noodlehaus\ConfigInterface;
use Noodlehaus\Parser\Php;

use function DI\create;

final readonly class ConfigProvider implements ProviderInterface
{
    public function register(ContainerBuilder $builder): void
    {
        $builder->addDefinitions(
            [
                ConfigConfigurator::class => create(ConfigConfigurator::class),
                ConfigInterface::class    => static function (ConfigConfigurator $configurator) {
                    return new Config($configurator->getPaths(), new Php());
                }
            ]
        );
    }
}
