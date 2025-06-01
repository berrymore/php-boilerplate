<?php

declare(strict_types=1);

namespace Component\Bus\Stamps;

final readonly class BusNameStamp
{
    public function __construct(private string $name)
    {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
