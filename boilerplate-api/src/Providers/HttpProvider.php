<?php

declare(strict_types=1);

namespace App\Api\Providers;

use App\Api\Http\AttributeLoader;
use App\Api\Http\Hooks\ErrorHook;
use App\Kernel\Lib\Serializer\CarbonNormalizer;
use App\Kernel\Lib\Serializer\DecimalNormalizer;
use App\Kernel\Lib\Serializer\UuidNormalizer;
use App\Kernel\ProviderInterface;
use Component\Http\Dispatcher\ArgumentResolver;
use Component\Http\Dispatcher\ArgumentResolverInterface;
use Component\Http\Dispatcher\AttributeHandlerProvider;
use Component\Http\Dispatcher\ControllerResolver;
use Component\Http\Dispatcher\ControllerResolverInterface;
use Component\Http\Dispatcher\ResponseFactory;
use Component\Http\Dispatcher\ResponseFactoryInterface;
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

final readonly class HttpProvider implements ProviderInterface
{
    public function register(ContainerBuilder $builder): void
    {
        $builder->addDefinitions(
            [
                Router::class                      => static function () {
                    return new Router(
                        new AttributeDirectoryLoader(
                            new FileLocator(__DIR__ . '/../../'),
                            new AttributeLoader()
                        ),
                        __DIR__ . '/../../',
                    );
                },
                'http.serializer'                  => static function () {
                    return new Serializer(
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
                    );
                },
                ControllerResolverInterface::class => autowire(ControllerResolver::class),
                ArgumentResolverInterface::class   => static function (ContainerInterface $container) {
                    $argumentResolver = new ArgumentResolver();

                    (new AttributeHandlerProvider($container->get('http.serializer')))->provide($argumentResolver);

                    //                    $argumentResolver->addAttributeHandler(
                    //                        new AttributeHandler(
                    //                            CurrentUser::class,
                    //                            function (Request $request) use ($container): ?User {
                    //                                return $container
                    //                                    ->get(UserRepository::class)
                    //                                    ->find($request->attributes->get('uid', 0));
                    //                            }
                    //                        )
                    //                    );

                    return $argumentResolver;
                },
                ResponseFactoryInterface::class    => static function (ContainerInterface $container) {
                    $responseFactory = new ResponseFactory($container->get('http.serializer'));

                    $responseFactory->pushHook(new ErrorHook($container->get('http.serializer')));

                    return $responseFactory;
                }
            ]
        );
    }
}
