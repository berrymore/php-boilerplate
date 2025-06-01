<?php

declare(strict_types=1);

namespace App\Kernel\Lib\Serializer;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final readonly class UuidNormalizer implements NormalizerInterface, DenormalizerInterface
{
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): UuidInterface
    {
        if (empty($data) && ! is_string($data)) {
            throw new InvalidArgumentException('Data must be a valid UUID');
        }

        return Uuid::fromString($data);
    }

    public function supportsDenormalization(
        mixed $data,
        string $type,
        ?string $format = null,
        array $context = []
    ): bool {
        return is_subclass_of($type, UuidInterface::class)
            || in_array($type, class_implements($type))
            || $type === UuidInterface::class;
    }

    public function normalize(
        mixed $data,
        ?string $format = null,
        array $context = []
    ): string {
        if (! $data instanceof UuidInterface) {
            throw new InvalidArgumentException('Data must implement UuidInterface');
        }

        return $data->toString();
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof UuidInterface;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            UuidInterface::class => true,
        ];
    }
}
