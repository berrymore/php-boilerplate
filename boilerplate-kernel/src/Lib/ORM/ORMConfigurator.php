<?php

declare(strict_types=1);

namespace App\Kernel\Lib\ORM;

final class ORMConfigurator
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
     * @return array
     */
    public function getPaths(): array
    {
        return $this->paths;
    }
}
