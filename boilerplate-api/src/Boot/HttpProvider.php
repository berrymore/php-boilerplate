<?php

declare(strict_types=1);

namespace App\Api\Boot;

use App\Api\Http\AttributeLoader;
use App\Api\Http\Hooks\ErrorHook;
use App\Kernel\Lib\Serializer\CarbonNormalizer;
use App\Kernel\Lib\Serializer\DecimalNormalizer;
use App\Kernel\Lib\Serializer\UuidNormalizer;
use Carina\Bootloader\Attribute\BeforeBoot;
use Carina\Http\Dispatcher\ArgumentResolver;
use Carina\Http\Dispatcher\ArgumentResolverInterface;
use Carina\Http\Dispatcher\AttributeHandlerProvider;
use Carina\Http\Dispatcher\ControllerResolver;
use Carina\Http\Dispatcher\ControllerResolverInterface;
use Carina\Http\Dispatcher\ResponseFactory;
use Carina\Http\Dispatcher\ResponseFactoryInterface;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\Routing\Loader\AttributeDirectoryLoader;
use Symfony\Component\Routing\Router;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\BackedEnumNormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;

use function DI\autowire;

#[BeforeBoot]
final readonly class HttpProvider
{
    public function __invoke(ContainerBuilder $builder): void
    {
        $builder->addDefinitions(
            [
                Router::class                      => static fn() => new Router(
                    new AttributeDirectoryLoader(
                        new FileLocator(__DIR__ . '/../../'),
                        new AttributeLoader()
                    ),
                    __DIR__ . '/../../',
                ),
                'http.serializer'                  => static fn() => new Serializer(
                    [
                        new BackedEnumNormalizer(),
                        new UuidNormalizer(),
                        new DecimalNormalizer(),
                        new CarbonNormalizer(),
                        new PropertyNormalizer(
                            null,
                            null,
                            new PhpDocExtractor()
                        ),
                        new ArrayDenormalizer(),
                    ],
                    [new JsonEncoder()]
                ),
                ControllerResolverInterface::class => autowire(ControllerResolver::class),
                ArgumentResolverInterface::class   => static fn(ContainerInterface $container
                ) => AttributeHandlerProvider::provide(
                    new ArgumentResolver(),
                    $container->get('http.serializer'),
                ),
                ResponseFactoryInterface::class    => static fn(ContainerInterface $container) => new ResponseFactory(
                    $container->get('http.serializer')
                )
                    ->pushHook(new ErrorHook($container->get('http.serializer')))
            ]
        );
    }
}
