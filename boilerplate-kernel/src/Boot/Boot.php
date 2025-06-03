<?php

declare(strict_types=1);

namespace App\Kernel\Boot;

use App\Kernel\Lib\Config\ConfigConfigurator;
use Carina\Bootloader\Attribute\OnBoot;

final readonly class Boot
{
    #[OnBoot]
    public function bootConfig(ConfigConfigurator $configurator): void
    {
        $configurator->addPath(__DIR__ . '/../../resources/config');
    }
}
