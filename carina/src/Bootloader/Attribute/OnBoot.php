<?php

declare(strict_types=1);

namespace Carina\Bootloader\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
final readonly class OnBoot
{
}
