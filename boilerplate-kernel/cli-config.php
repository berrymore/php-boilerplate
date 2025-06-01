<?php

require __DIR__ . '/bootstrap.php';

use App\Kernel\Lib\ORM\ORMRegistry;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\Migrations\DependencyFactory;

$container = boot();

return DependencyFactory::fromEntityManager(
    new PhpFile(__DIR__ . '/migrations.php'),
    new ExistingEntityManager($container->get(ORMRegistry::class)->getEntityManager())
);
