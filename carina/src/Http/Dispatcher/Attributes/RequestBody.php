<?php

declare(strict_types=1);

namespace Carina\Http\Dispatcher\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
final class RequestBody
{
}
