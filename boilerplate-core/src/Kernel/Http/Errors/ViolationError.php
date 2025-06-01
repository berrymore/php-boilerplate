<?php

declare(strict_types=1);

namespace App\Kernel\Http\Errors;

use App\Kernel\Http\Attribute\Error;

#[Error('2e30f6c3-b5e8-43c3-8182-8e70c2564b89')]
final class ViolationError
{
    /**
     * @param  string                                    $message
     * @param  \App\Kernel\Http\Models\ViolationModel[]  $violations
     */
    public function __construct(private string $message, private array $violations)
    {
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return array
     */
    public function getViolations(): array
    {
        return $this->violations;
    }
}
