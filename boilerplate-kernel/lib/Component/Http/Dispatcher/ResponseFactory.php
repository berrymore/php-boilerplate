<?php

declare(strict_types=1);

namespace Component\Http\Dispatcher;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class ResponseFactory implements ResponseFactoryInterface
{
    /** @var callable[] */
    private array $hooks;

    public function __construct(private readonly SerializerInterface $serializer)
    {
        $this->hooks = [];
    }

    /**
     * @param  callable  $hook
     */
    public function pushHook(callable $hook): void
    {
        $this->hooks[] = $hook;
    }

    /**
     * @throws \ReflectionException
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function create($data, Request $request): Response
    {
        foreach ($this->hooks as $hook) {
            $hookResult = $hook($data, $request);

            if ($hookResult instanceof Response) {
                return $hookResult;
            }
        }

        if ($data instanceof Response) {
            return $data;
        }

        if (is_array($data)) {
            return new JsonResponse(
                $this->serializer->serialize($data, 'json', [AbstractObjectNormalizer::SKIP_NULL_VALUES => true]),
                200,
                [],
                true
            );
        }

        if (empty($data)) {
            return new Response();
        }

        if (is_scalar($data)) {
            return new Response($data);
        }

        if (is_object($data)) {
            return new JsonResponse(
                $this->serializer->serialize(
                    $data,
                    'json',
                    [
                        AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
                        AbstractObjectNormalizer::PRESERVE_EMPTY_OBJECTS => true,
                    ]
                ),
                200,
                [],
                true
            );
        }

        throw new InvalidArgumentException('Cannot create a Response based on the specified data');
    }
}
