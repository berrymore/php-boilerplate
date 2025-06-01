<?php

declare(strict_types=1);

namespace App\Kernel\Lib\Serializer;

use Decimal\Decimal;
use InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final readonly class DecimalNormalizer implements NormalizerInterface, DenormalizerInterface
{
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): Decimal
    {
        if (empty($data) || ! is_string($data) || ! is_numeric($data)) {
            throw new InvalidArgumentException('Data must be a valid numeric value');
        }

        return new Decimal($data);
    }

    public function supportsDenormalization(
        mixed $data,
        string $type,
        ?string $format = null,
        array $context = []
    ): bool {
        return is_string($data) && is_numeric($data);
    }

    public function normalize(
        mixed $data,
        ?string $format = null,
        array $context = []
    ): string {
        if (! $data instanceof Decimal) {
            throw new InvalidArgumentException('Data must be a Decimal object');
        }

        return $data->__toString();
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Decimal;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Decimal::class => true,
        ];
    }
}
