<?php

declare(strict_types=1);

namespace App\Kernel\Lib\Config;

final class ConfigConfigurator
{
    /** @var string[] */
    private array $paths;

    public function __construct()
    {
        $this->paths = [];
    }

    /**
     * @param  string  $path
     */
    public function addPath(string $path): void
    {
        $this->paths[] = $path;
    }

    /**
     * @return string[]
     */
    public function getPaths(): array
    {
        return $this->paths;
    }
}
