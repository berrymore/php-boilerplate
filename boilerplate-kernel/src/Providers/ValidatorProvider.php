<?php

declare(strict_types=1);

namespace App\Kernel\Providers;

use Component\Kernel\ProviderInterface;
use DI\ContainerBuilder;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final readonly class ValidatorProvider implements ProviderInterface
{
    public function register(ContainerBuilder $builder): void
    {
        $builder->addDefinitions(
            array(
                ValidatorInterface::class => static fn() => Validation::createValidator()
            )
        );
    }
}
