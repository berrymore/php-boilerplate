<?php

declare(strict_types=1);

namespace App\Kernel\Lib\Serializer;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final readonly class CarbonNormalizer implements NormalizerInterface, DenormalizerInterface
{
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): CarbonImmutable
    {
        return CarbonImmutable::parse($data);
    }

    public function supportsDenormalization(
        mixed $data,
        string $type,
        ?string $format = null,
        array $context = []
    ): bool {
        return is_string($data);
    }

    public function normalize(
        mixed $data,
        ?string $format = null,
        array $context = []
    ): string {
        if (! $data instanceof CarbonInterface) {
            throw new InvalidArgumentException('Data must be a CarbonInterface object');
        }

        return $data->toW3cString();
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof CarbonInterface;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            CarbonInterface::class => true,
        ];
    }
}
