<?php

declare(strict_types=1);

namespace App\Api;

use App\Api\Providers\HttpProvider;
use Component\Kernel\ModuleInterface;

final readonly class Module implements ModuleInterface
{
    public function getProviders(): array
    {
        return [
            HttpProvider::class,
        ];
    }
}
