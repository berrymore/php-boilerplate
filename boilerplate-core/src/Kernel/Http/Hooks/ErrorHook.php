<?php

declare(strict_types=1);

namespace App\Kernel\Http\Hooks;

use App\Kernel\Http\Attribute\Error;
use ReflectionClass;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final readonly class ErrorHook
{
    public function __construct(private NormalizerInterface $normalizer)
    {
    }

    /**
     * @param  mixed  $data
     *
     * @return \Symfony\Component\HttpFoundation\Response|null
     * @throws \ReflectionException
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function __invoke(mixed $data): ?Response
    {
        if (is_object($data)) {
            $reflection = new ReflectionClass($data);

            foreach ($reflection->getAttributes(Error::class) as $attr) {
                $attrInstance = $attr->newInstance();

                $result         = $this->normalizer->normalize($data, 'json');
                $result['code'] = $attrInstance->getCode();

                return new JsonResponse($result, Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }

        return null;
    }
}
