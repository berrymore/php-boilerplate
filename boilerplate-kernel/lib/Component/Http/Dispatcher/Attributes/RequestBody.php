<?php

declare(strict_types=1);

namespace Component\Http\Dispatcher\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
final class RequestBody
{
}
