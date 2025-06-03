<?php

declare(strict_types=1);

namespace App\Kernel\Lib\Bus\Middleware;

use App\Kernel\Lib\ORM\ORMRegistry;
use Carina\Bus\EnvelopeInterface;
use Carina\Bus\Middleware\MiddlewareInterface;
use Carina\Bus\Middleware\StackInterface;
use Throwable;

final readonly class WrapInTransactionMiddleware implements MiddlewareInterface
{
    public function __construct(private ORMRegistry $ormRegistry)
    {
    }

    /**
     * @throws \Throwable
     */
    public function handle(EnvelopeInterface $envelope, StackInterface $stack): EnvelopeInterface
    {
        $orm = $this->ormRegistry->getEntityManager();

        try {
            $orm->beginTransaction();

            $result = $stack->next()->handle($envelope, $stack);

            $orm->flush();
            $orm->commit();

            return $result;
        } catch (Throwable $e) {
            $orm->rollBack();

            throw $e;
        } finally {
            $orm->close();
        }
    }
}
