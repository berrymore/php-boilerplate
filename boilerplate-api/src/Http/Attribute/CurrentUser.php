<?php

declare(strict_types=1);

namespace App\Api\Http\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
final readonly class CurrentUser
{
}
