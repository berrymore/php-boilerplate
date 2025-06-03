<?php

declare(strict_types=1);

namespace App\Kernel\Boot;

use Carina\Bootloader\Attribute\BeforeBoot;
use DI\ContainerBuilder;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[BeforeBoot]
final readonly class ValidatorProvider
{
    public function __invoke(ContainerBuilder $builder): void
    {
        $builder->addDefinitions(
            array(
                ValidatorInterface::class => static fn() => Validation::createValidator()
            )
        );
    }
}
