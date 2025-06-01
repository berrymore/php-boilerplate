<?php

declare(strict_types=1);

namespace App\Kernel\Lib\ORM;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;

interface ORMRegistry
{
    public const string DEFAULT_NAME = 'default';

    /**
     * @param  string  $name
     *
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager(string $name = self::DEFAULT_NAME): EntityManager;

    /**
     * @param  string  $name
     *
     * @return \Doctrine\DBAL\Connection
     */
    public function getConnection(string $name = self::DEFAULT_NAME): Connection;
}
