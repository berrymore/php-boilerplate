<?php

declare(strict_types=1);

namespace App\Api\Http\Models;

use Symfony\Component\Validator\ConstraintViolationInterface;

final readonly class ViolationModel
{
    public function __construct(private string $path, private string $message)
    {
    }

    /**
     * @param  \Symfony\Component\Validator\ConstraintViolationInterface  $violation
     *
     * @return self
     */
    public static function fromSymfonyViolation(ConstraintViolationInterface $violation): self
    {
        return new self($violation->getPropertyPath(), $violation->getMessage());
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}
