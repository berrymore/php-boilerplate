<?php

declare(strict_types=1);

namespace App\Welcome;

use App\Kernel\ModuleInterface;
use App\Welcome\Providers\WelcomeProvider;

final readonly class Module implements ModuleInterface
{
    public function getProviders(): array
    {
        return [
            WelcomeProvider::class,
        ];
    }
}
