<?php

declare(strict_types=1);

namespace App\Api\Http\Middleware;

use App\Api\Http\Attribute\ValidateWith;
use App\Api\Http\Errors\ViolationError;
use App\Api\Http\Models\ViolationModel;
use Carina\Http\Dispatcher\ArgumentResolverInterface;
use Carina\Http\Dispatcher\ControllerResolverInterface;
use Carina\Http\Dispatcher\ResponseFactoryInterface;
use Carina\Http\MiddlewareInterface;
use Carina\Http\RequestHandlerInterface;
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
            /** @var \App\Api\Http\Attribute\ValidateWith $attrInstance */
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
