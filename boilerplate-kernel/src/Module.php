<?php

declare(strict_types=1);

namespace App\Kernel;

use App\Kernel\Providers\BusProvider;
use App\Kernel\Providers\ConfigProvider;
use App\Kernel\Providers\ORMProvider;
use App\Kernel\Providers\ValidatorProvider;

final readonly class Module implements ModuleInterface
{
    public function getProviders(): array
    {
        return [
            ConfigProvider::class,
            BusProvider::class,
            ValidatorProvider::class,
            ORMProvider::class,
        ];
    }
}
