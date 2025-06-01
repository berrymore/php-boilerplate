<?php

declare(strict_types=1);

namespace App\Kernel\Lib\ORM;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;

/**
 * @phpstan-template T of object
 */
abstract readonly class AbstractRepository
{
    public function __construct(protected ORMRegistry $registry)
    {
    }

    abstract protected function getEntityName(): string;

    /**
     * @param  int  $id
     *
     * @return object
     * @phpstan-return T
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function getEntity(int $id): object
    {
        $entity = $this->getEntityManager()->find($this->getEntityName(), $id);

        if ($entity === null) {
            throw EntityNotFoundException::fromClassNameAndIdentifier($this->getEntityName(), [(string) $id]);
        }

        return $entity;
    }

    /**
     * @param  string  $name
     *
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEntityManager(string $name = ORMRegistry::DEFAULT_NAME): EntityManager
    {
        return $this->registry->getEntityManager($name);
    }
}
