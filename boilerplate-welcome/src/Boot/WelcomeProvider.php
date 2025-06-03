<?php

declare(strict_types=1);

namespace App\Welcome\Boot;

use App\Welcome\Adapter\Repositories\QuoteRepositoryImpl;
use App\Welcome\Application\Repositories\QuoteRepository;
use Carina\Bootloader\Attribute\BeforeBoot;
use DI\ContainerBuilder;

use function DI\autowire;

#[BeforeBoot]
final readonly class WelcomeProvider
{
    public function __invoke(ContainerBuilder $builder): void
    {
        $builder->addDefinitions(
            [
                QuoteRepository::class => autowire(QuoteRepositoryImpl::class),
            ]
        );
    }
}
