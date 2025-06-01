<?php

declare(strict_types=1);

namespace App\Kernel\Lib\ORM;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use SensitiveParameter;
use Webmozart\Assert\Assert;

class ORMRegistryImpl implements ORMRegistry
{
    /** @var array<string, array> */
    private array $registry;

    /** @var array<string, \Doctrine\DBAL\Connection> */
    private array $connections;

    /** @var array<string, \Doctrine\ORM\EntityManager> */
    private array $entityManagers;

    /** @var callable[] */
    private array $hooks;

    public function __construct()
    {
        $this->registry       = [];
        $this->connections    = [];
        $this->entityManagers = [];
        $this->hooks          = [];
    }

    public function getEntityManager(string $name = self::DEFAULT_NAME): EntityManager
    {
        if (($entityManager = $this->entityManagers[$name] ?? null) && ! $entityManager->isOpen()) {
            unset($this->entityManagers[$name]);
        }

        if (! isset($this->entityManagers[$name])) {
            $this->entityManagers[$name] = new EntityManager(
                $this->getConnection($name),
                $this->registry[$name][1],
            );

            foreach ($this->hooks as $hook) {
                call_user_func($hook, $this->entityManagers[$name]);
            }
        }

        return $this->entityManagers[$name];
    }

    public function getConnection(string $name = self::DEFAULT_NAME): Connection
    {
        Assert::keyExists($this->registry, $name);

        if (! isset($this->connections[$name])) {
            $this->connections[$name] = DriverManager::getConnection($this->registry[$name][0]);
        }

        return $this->connections[$name];
    }

    /**
     * @param  string                       $name
     * @param  array                        $connection
     * @param  \Doctrine\ORM\Configuration  $configuration
     */
    public function register(string $name, #[SensitiveParameter] array $connection, Configuration $configuration): void
    {
        $this->registry[$name] = [$connection, $configuration];
    }

    /**
     * @param  callable  $hook
     */
    public function addPostHook(callable $hook): void
    {
        $this->hooks[] = $hook;
    }
}
