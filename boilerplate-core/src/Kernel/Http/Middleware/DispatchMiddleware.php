<?php

declare(strict_types=1);

namespace App\Kernel\Http\Middleware;

use App\Kernel\Http\Attribute\ValidateWith;
use App\Kernel\Http\Errors\ViolationError;
use App\Kernel\Http\Models\ViolationModel;
use Component\Http\Dispatcher\ArgumentResolverInterface;
use Component\Http\Dispatcher\ControllerResolverInterface;
use Component\Http\Dispatcher\ResponseFactoryInterface;
use Component\Http\MiddlewareInterface;
use Component\Http\RequestHandlerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final readonly class DispatchMiddleware implements MiddlewareInterface
{
    public function __construct(
        private ControllerResolverInterface $controllerResolver,
        private ArgumentResolverInterface $argumentResolver,
        private ResponseFactoryInterface $responseFactory,
        private ContainerInterface $container,
    ) {
    }

    public function process(Request $request, RequestHandlerInterface $requestHandler): Response
    {
        $controller = $this->controllerResolver->resolve($request);

        $reflection = $controller->getReflectionFunction();

        foreach ($reflection->getAttributes(ValidateWith::class) as $attr) {
            /** @var \App\Kernel\Http\Attribute\ValidateWith $attrInstance */
            $attrInstance = $attr->newInstance();
            $validator    = $this->container->get($attrInstance->getValidator());

            /** @var \Symfony\Component\Validator\ConstraintViolationListInterface $violations */
            $violations = call_user_func($validator, $request);

            if ($violations->count() > 0) {
                $violationModels = [];

                foreach ($violations as $violation) {
                    $violationModels[] = ViolationModel::fromSymfonyViolation($violation);
                }

                return $this->responseFactory->create(new ViolationError('', $violationModels), $request);
            }
        }

        return $this->responseFactory->create(
            call_user_func_array(
                $controller->getCallable(),
                $this->argumentResolver->resolve($request, $controller)
            ),
            $request
        );
    }
}
