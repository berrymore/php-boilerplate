<?php

declare(strict_types=1);

namespace App\Api\Http\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final readonly class Error
{
    public function __construct(private string $code)
    {
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }
}
