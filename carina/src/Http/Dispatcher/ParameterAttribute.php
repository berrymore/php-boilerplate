<?php

declare(strict_types=1);

namespace Carina\Http\Dispatcher;

final readonly class ParameterAttribute
{
    public function __construct(private string $parameter, private object $attribute)
    {
    }

    /**
     * @return string
     */
    public function getParameter(): string
    {
        return $this->parameter;
    }

    /**
     * @return object
     */
    public function getAttribute(): object
    {
        return $this->attribute;
    }
}
