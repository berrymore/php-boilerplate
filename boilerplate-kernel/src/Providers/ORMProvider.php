<?php

declare(strict_types=1);

namespace App\Kernel\Providers;

use App\Kernel\Lib\ORM\ORMConfigurator;
use App\Kernel\Lib\ORM\ORMRegistry;
use App\Kernel\Lib\ORM\ORMRegistryImpl;
use Carbon\Doctrine\CarbonImmutableType;
use Carbon\Doctrine\CarbonType;
use Component\Kernel\ProviderInterface;
use DI\ContainerBuilder;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;
use Doctrine\ORM\ORMSetup;
use Gedmo\Timestampable\TimestampableListener;
use Noodlehaus\ConfigInterface;
use Ramsey\Uuid\Doctrine\UuidType;

use function DI\create;

final readonly class ORMProvider implements ProviderInterface
{
    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function register(ContainerBuilder $builder): void
    {
        $builder->addDefinitions(
            [
                ORMConfigurator::class => create(ORMConfigurator::class),
                ORMRegistry::class     => static function (ConfigInterface $config, ORMConfigurator $configurator) {
                    $registry = new ORMRegistryImpl();

                    $configuration = ORMSetup::createXMLMetadataConfiguration(
                        $configurator->getPaths(),
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
