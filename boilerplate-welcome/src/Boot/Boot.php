<?php

declare(strict_types=1);

namespace App\Welcome\Boot;

use App\Kernel\Lib\ORM\ORMConfigurator;
use Carina\Bootloader\Attribute\OnBoot;

final readonly class Boot
{
    #[OnBoot]
    public function bootOrm(ORMConfigurator $configurator): void
    {
        $configurator->addPath(__DIR__ . '/../../resources/entities');
    }
}
