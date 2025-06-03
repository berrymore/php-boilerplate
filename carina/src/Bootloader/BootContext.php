<?php

declare(strict_types=1);

namespace Carina\Bootloader;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use ReflectionMethod;
use Throwable;

class BootContext
{
    /** @var \ReflectionClass[][] */
    private array $cache;

    /**
     * @param  string[]  $paths
     * @param  string[]  $attributes
     */
    public function __construct(private array $paths, private array $attributes)
    {
        $this->cache = [];
    }

    /**
     * @param  string  $attribute
     *
     * @return \ReflectionClass[]
     */
    public function getAttributedBy(string $attribute): array
    {
        return $this->cache[$attribute] ?? [];
    }

    public function refresh(): void
    {
        $this->cache = [];

        foreach ($this->paths as $path) {
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));

            foreach ($files as $file) {
                if ($file->getExtension() !== 'php') {
                    continue;
                }

                $fqcn = $this->extractFQCN($file->getPathname());

                if ($fqcn && class_exists($fqcn)) {
                    try {
                        $reflection = new ReflectionClass($fqcn);
                        foreach ($this->attributes as $attribute) {
                            if (! empty($reflection->getAttributes($attribute))) {
                                $this->cache[$attribute][] = $reflection;
                            }
                        }

                        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
                            foreach ($this->attributes as $attribute) {
                                if (! empty($method->getAttributes($attribute))) {
                                    $this->cache[$attribute][] = $method;
                                }
                            }
                        }
                    } catch (Throwable) {
                    }
                }
            }
        }
    }

    function extractFqcn(string $file): ?string
    {
        $contents = file_get_contents($file);
        if ($contents === false) {
            return null;
        }

        $tokens = token_get_all($contents);

        $namespace = '';
        $class     = null;

        for ($i = 0, $count = count($tokens); $i < $count; $i++) {
            $token = $tokens[$i];

            if (! is_array($token)) {
                continue;
            }

            if ($token[0] === T_NAMESPACE) {
                $j = $i + 1;
                while (isset($tokens[$j]) && is_array($tokens[$j]) && $tokens[$j][0] !== T_NAME_QUALIFIED) {
                    $j++;
                }
                $namespace = $tokens[$j][1];
            }

            if (in_array($token[0], [T_CLASS, T_INTERFACE, T_ENUM])) {
                $prevToken = $tokens[$i - 1] ?? null;
                if (is_array($prevToken) && $prevToken[0] === T_NEW) {
                    continue;
                }

                $j = $i + 1;
                while (isset($tokens[$j]) && $tokens[$j][0] !== T_STRING) {
                    $j++;
                }

                $class = $tokens[$j][1] ?? null;
                break;
            }
        }

        if ($class) {
            return $namespace ? "$namespace\\$class" : $class;
        }

        return null;
    }
}
