<?php

declare(strict_types=1);

namespace App\Kernel\Providers;

use App\Kernel\Lib\ORM\ORMRegistry;
use App\Kernel\Lib\ORM\ORMRegistryImpl;
use App\Kernel\ProviderInterface;
use Carbon\Doctrine\CarbonImmutableType;
use Carbon\Doctrine\CarbonType;
use DI\ContainerBuilder;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;
use Doctrine\ORM\ORMSetup;
use Gedmo\Timestampable\TimestampableListener;
use Noodlehaus\ConfigInterface;
use Psr\Container\ContainerInterface;
use Ramsey\Uuid\Doctrine\UuidBinaryType;
use Ramsey\Uuid\Doctrine\UuidType;

final readonly class ORMProvider implements ProviderInterface
{
    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function register(ContainerBuilder $builder): void
    {
        $builder->addDefinitions(
            [
                ORMRegistry::class => static function (ContainerInterface $container) {
                    $registry = new ORMRegistryImpl();
                    $config   = $container->get(ConfigInterface::class);

                    $configuration = ORMSetup::createXMLMetadataConfiguration(
                        [__DIR__ . '/../../../boilerplate-welcome/resources/entities'],
                        isXsdValidationEnabled: false
                    );

                    $configuration->setNamingStrategy(new UnderscoreNamingStrategy());

                    $registry->register(
                        ORMRegistry::DEFAULT_NAME,
                        $config->get('orm.default.connection', []),
                        $configuration
                    );

                    $registry->addPostHook(
                        function (EntityManager $em) {
                            $em->getEventManager()->addEventSubscriber(new TimestampableListener());
                        }
                    );

                    return $registry;
                }
            ]
        );

        Type::addType('uuid', UuidType::class);
        Type::overrideType('datetime_immutable', CarbonImmutableType::class);
        Type::overrideType('datetime', CarbonType::class);
    }
}
