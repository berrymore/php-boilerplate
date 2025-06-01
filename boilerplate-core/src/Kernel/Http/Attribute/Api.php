<?php

declare(strict_types=1);

namespace App\Kernel\Http\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final readonly class Api
{
    public function __construct(private string $service, private string $controller, private string $version = 'v1')
    {
    }

    /**
     * @return string
     */
    public function getService(): string
    {
        return $this->service;
    }

    /**
     * @return string
     */
    public function getController(): string
    {
        return $this->controller;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }
}
