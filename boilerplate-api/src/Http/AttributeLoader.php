<?php

declare(strict_types=1);

namespace App\Api\Http;

use App\Api\Http\Attribute\Api as ApiAttr;
use ReflectionClass;
use ReflectionMethod;
use Symfony\Component\Routing\Attribute\Route as RouteAttr;
use Symfony\Component\Routing\Loader\AttributeClassLoader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

final class AttributeLoader extends AttributeClassLoader
{
    protected function configureRoute(
        Route $route,
        ReflectionClass $class,
        ReflectionMethod $method,
        object $attr
    ): void {
        $route->setDefault('_service', $class->getName());
        $route->setDefault('_method', $method->getName());
    }

    protected function addRoute(
        RouteCollection $collection,
        object $attr,
        array $globals,
        ReflectionClass $class,
        ReflectionMethod $method
    ): void {
        $this->setupApiRoute($attr, $class);

        parent::addRoute($collection, $attr, $globals, $class, $method);
    }

    /**
     * @param  object            $routeAttr
     * @param  \ReflectionClass  $class
     */
    private function setupApiRoute(object $routeAttr, ReflectionClass $class): void
    {
        if ($routeAttr instanceof RouteAttr) {
            $attrs = $class->getAttributes(ApiAttr::class);

            if (! empty($attrs)) {
                /** @var \App\Api\Http\Attribute\Api $attr */
                $attr = $attrs[0]->newInstance();

                $routeAttr->setPath(
                    sprintf(
                        '/%s/%s/%s%s',
                        $attr->getVersion(),
                        $attr->getService(),
                        $attr->getController(),
                        $routeAttr->getPath()
                    )
                );

                $routeAttr->setName(
                    sprintf(
                        'api_%s_%s_%s_%s',
                        $attr->getVersion(),
                        $attr->getService(),
                        $attr->getController(),
                        $routeAttr->getName()
                    )
                );
            }
        }
    }
}
