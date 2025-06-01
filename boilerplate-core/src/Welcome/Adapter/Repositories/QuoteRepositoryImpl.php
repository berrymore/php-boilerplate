<?php

declare(strict_types=1);

namespace App\Welcome\Adapter\Repositories;

use App\Kernel\Lib\ORM\AbstractRepository;
use App\Welcome\Application\Repositories\QuoteRepository;
use App\Welcome\Domain\Quote\Quote;

readonly class QuoteRepositoryImpl extends AbstractRepository implements QuoteRepository
{
    public function findRandom(): Quote
    {
        $count = (int) $this->getEntityManager()->createQueryBuilder()
            ->select('COUNT(q.id)')
            ->from($this->getEntityName(), 'q')
            ->getQuery()
            ->getSingleScalarResult();

        return $this->getEntityManager()->createQueryBuilder()
            ->select('q')
            ->from($this->getEntityName(), 'q')
            ->setFirstResult(random_int(0, $count - 1))
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();
    }

    protected function getEntityName(): string
    {
        return Quote::class;
    }
}
