<?php

declare(strict_types=1);

namespace App\Kernel\Boot;

use App\Kernel\Lib\Config\ConfigConfigurator;
use Carina\Bootloader\Attribute\BeforeBoot;
use DI\ContainerBuilder;
use Noodlehaus\Config;
use Noodlehaus\ConfigInterface;
use Noodlehaus\Parser\Php;

use function DI\create;

#[BeforeBoot]
final readonly class ConfigProvider
{
    public function __invoke(ContainerBuilder $builder): void
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
