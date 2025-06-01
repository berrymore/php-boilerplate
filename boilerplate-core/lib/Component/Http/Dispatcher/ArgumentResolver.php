<?php

declare(strict_types=1);

namespace Component\Http\Dispatcher;

use Component\Http\Dispatcher\Attributes\Controller;
use InvalidArgumentException;
use ReflectionNamedType;
use Symfony\Component\HttpFoundation\Request;

class ArgumentResolver implements ArgumentResolverInterface
{
    /** @var \Component\Http\Dispatcher\AttributeHandler[] */
    private array $attributeHandlers;

    public function __construct()
    {
        $this->attributeHandlers = [];
    }

    /**
     * @throws \ReflectionException
     */
    public function resolve(Request $request, ControllerInterface $controller): array
    {
        $result               = [];
        $reflectionFunction   = $controller->getReflectionFunction();
        $reflectionClass      = $controller->getReflectionClass();
        $hasClosureScopeClass = $controller->hasClassScope();
        $isController         =
            $hasClosureScopeClass && $reflectionClass->getAttributes(Controller::class) !== [];

        // Compute attributes for parameters
        /** @var \ReflectionAttribute[][] $parameterAttributes */
        $parameterAttributes = [];

        if ($isController) {
            $parameters = $reflectionClass->getMethod($reflectionFunction->getName())->getParameters();

            foreach ($parameters as $parameter) {
                foreach ($parameter->getAttributes() as $attribute) {
                    $attrInstance = $attribute->newInstance();

                    if ($this->hasAttributeHandler($attrInstance)) {
                        $parameterAttributes[$parameter->getName()][] = $attrInstance;
                    }
                }
            }
        }

        // Resolve values for parameters
        foreach ($reflectionFunction->getParameters() as $parameter) {
            if ($isController) {
                $type = $parameter->getType();

                if ($type instanceof ReflectionNamedType && ! $type->isBuiltin()) {
                    if (is_subclass_of($type->getName(), Request::class) || $type->getName() === Request::class) {
                        $result[] = $request;

                        continue;
                    }
                }

                $attributes = $parameterAttributes[$parameter->getName()] ?? [];

                if (empty($attributes)) {
                    throw new InvalidArgumentException(
                        sprintf('Cannot resolve value for the "%s" parameter', $parameter->getName())
                    );
                }

                foreach ($attributes as $attribute) {
                    $result[] = $this->getAttributeHandler($attribute)->handle(
                        $request,
                        $parameter,
                        $attribute
                    );
                }
            }
        }

        return $result;
    }

    /**
     * @param  \Component\Http\Dispatcher\AttributeHandler  $attributeHandler
     */
    public function addAttributeHandler(AttributeHandler $attributeHandler): void
    {
        $this->attributeHandlers[$attributeHandler->getAttribute()] = $attributeHandler;
    }

    /**
     * @param  object  $attribute
     *
     * @return bool
     */
    public function hasAttributeHandler(object $attribute): bool
    {
        return isset($this->attributeHandlers[get_class($attribute)]);
    }

    /**
     * @param  object  $attribute
     *
     * @return \Component\Http\Dispatcher\AttributeHandler
     */
    private function getAttributeHandler(object $attribute): AttributeHandler
    {
        $class = get_class($attribute);

        if (isset($this->attributeHandlers[$class])) {
            return $this->attributeHandlers[$class];
        }

        throw new InvalidArgumentException(sprintf('There is no handler for the "%s" attribute', $class));
    }
}
