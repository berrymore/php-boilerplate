<?php

declare(strict_types=1);

namespace App\Kernel\Providers;

use App\Kernel\ProviderInterface;
use DI\ContainerBuilder;
use Noodlehaus\Config;
use Noodlehaus\ConfigInterface;
use Noodlehaus\Parser\Php;

final readonly class ConfigProvider implements ProviderInterface
{
    public function register(ContainerBuilder $builder): void
    {
        $builder->addDefinitions(
            [
                ConfigInterface::class => static function () {
                    return new Config(__DIR__ . '/../../../resources/config', new Php());
                }
            ]
        );
    }
}
