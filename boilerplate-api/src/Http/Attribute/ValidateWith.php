<?php

declare(strict_types=1);

namespace App\Api\Http\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
final readonly class ValidateWith
{
    public function __construct(private string $validator)
    {
    }

    /**
     * @return string
     */
    public function getValidator(): string
    {
        return $this->validator;
    }
}
