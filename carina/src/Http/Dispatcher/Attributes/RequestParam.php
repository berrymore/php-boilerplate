<?php

declare(strict_types=1);

namespace Carina\Http\Dispatcher\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
final class RequestParam
{
    /** @var string|null */
    private ?string $key;

    /** @var mixed */
    private mixed $default;

    /**
     * @param  string|null  $key
     * @param  mixed        $default
     */
    public function __construct(?string $key = null, mixed $default = null)
    {
        $this->key     = $key;
        $this->default = $default;
    }

    /**
     * @return string|null
     */
    public function getKey(): ?string
    {
        return $this->key;
    }

    /**
     * @return mixed
     */
    public function getDefault(): mixed
    {
        return $this->default;
    }
}
