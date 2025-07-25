<?php

declare(strict_types=1);

namespace Carina\Http\Dispatcher\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
final class UserAgent
{
    /** @var mixed */
    private mixed $default;

    /**
     * @param  mixed  $default
     */
    public function __construct(mixed $default = null)
    {
        $this->default = $default;
    }

    /**
     * @return mixed
     */
    public function getDefault(): mixed
    {
        return $this->default;
    }
}
