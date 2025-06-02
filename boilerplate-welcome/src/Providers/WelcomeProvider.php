<?php

declare(strict_types=1);

namespace App\Welcome\Providers;

use App\Welcome\Adapter\Repositories\QuoteRepositoryImpl;
use App\Welcome\Application\Repositories\QuoteRepository;
use Component\Kernel\ProviderInterface;
use DI\ContainerBuilder;

use function DI\autowire;

final readonly class WelcomeProvider implements ProviderInterface
{
    public function register(ContainerBuilder $builder): void
    {
        $builder->addDefinitions(
            [
                QuoteRepository::class => autowire(QuoteRepositoryImpl::class),
            ]
        );
    }
}
