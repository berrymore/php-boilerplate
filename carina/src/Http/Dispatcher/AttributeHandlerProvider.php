<?php

declare(strict_types=1);

namespace Carina\Http\Dispatcher;

use Carina\Http\Dispatcher\Attributes\ClientIp;
use Carina\Http\Dispatcher\Attributes\CookieValue;
use Carina\Http\Dispatcher\Attributes\CurrentRoute;
use Carina\Http\Dispatcher\Attributes\PathVariable;
use Carina\Http\Dispatcher\Attributes\RequestAttr;
use Carina\Http\Dispatcher\Attributes\RequestBody;
use Carina\Http\Dispatcher\Attributes\RequestHeader;
use Carina\Http\Dispatcher\Attributes\RequestLocale;
use Carina\Http\Dispatcher\Attributes\RequestParam;
use Carina\Http\Dispatcher\Attributes\UserAgent;
use InvalidArgumentException;
use ReflectionNamedType;
use ReflectionParameter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final readonly class AttributeHandlerProvider
{
    public function __construct(private DenormalizerInterface $denormalizer)
    {
    }

    /**
     * @param  \Carina\Http\Dispatcher\ArgumentResolver  $argumentResolver
     */
    public function provide(ArgumentResolver $argumentResolver): void
    {
        $handlers = [
            new AttributeHandler(
                RequestBody::class,
                function (Request $request, ReflectionParameter $parameter) {
                    $type = $parameter->getType();

                    if ($type instanceof ReflectionNamedType && ! $type->isBuiltin()) {
                        return $this->denormalizer->denormalize($request->request->all(), $type->getName());
                    }

                    throw new InvalidArgumentException('RequestBody can only be used with typed parameters');
                }
            ),
            new AttributeHandler(
                RequestHeader::class,
                function (Request $request, ReflectionParameter $parameter, RequestHeader $attribute) {
                    return $request->headers->get(
                        $attribute->getKey() ?? $parameter->getName(),
                        $attribute->getDefault()
                    );
                }
            ),
            new AttributeHandler(
                RequestParam::class,
                function (Request $request, ReflectionParameter $parameter, RequestParam $attribute) {
                    return $request->query->get(
                        $attribute->getKey() ?? $parameter->getName(),
                        $attribute->getDefault()
                    );
                }
            ),
            new AttributeHandler(
                PathVariable::class,
                function (Request $request, ReflectionParameter $parameter, PathVariable $attribute) {
                    return $request->attributes->get(
                        $attribute->getKey() ?? $parameter->getName(),
                        $attribute->getDefault()
                    );
                }
            ),
            new AttributeHandler(
                RequestAttr::class,
                function (Request $request, ReflectionParameter $parameter, RequestAttr $attribute) {
                    return $request->attributes->get(
                        $attribute->getKey() ?? $parameter->getName(),
                        $attribute->getDefault()
                    );
                }
            ),
            new AttributeHandler(
                CookieValue::class,
                function (Request $request, ReflectionParameter $parameter, CookieValue $attribute) {
                    return $request->cookies->get(
                        $attribute->getKey() ?? $parameter->getName(),
                        $attribute->getDefault()
                    );
                }
            ),
            new AttributeHandler(
                ClientIp::class,
                function (Request $request, ReflectionParameter $parameter, ClientIp $attribute) {
                    return $request->getClientIp() ?? $attribute->getDefault();
                }
            ),
            new AttributeHandler(
                UserAgent::class,
                function (Request $request, ReflectionParameter $parameter, UserAgent $attribute) {
                    return $request->headers->get('User-Agent', $attribute->getDefault());
                }
            ),
            new AttributeHandler(
                RequestLocale::class,
                function (Request $request) {
                    return $request->getLocale();
                }
            ),
            new AttributeHandler(
                CurrentRoute::class,
                function (Request $request) {
                    return $request->attributes->get('_route');
                }
            )
        ];

        foreach ($handlers as $handler) {
            $argumentResolver->addAttributeHandler($handler);
        }
    }
}
