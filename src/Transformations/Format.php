<?php

namespace Backstage\UploadcareTransformations\Transformations;

use Backstage\UploadcareTransformations\Transformations\Enums\Format as FormatEnum;
use Backstage\UploadcareTransformations\Transformations\Interfaces\TransformationInterface;

class Format implements TransformationInterface
{
    final public const FORMAT = 'format';

    public static function transform(...$args): array
    {
        $format = $args[0];

        $formatValue = is_string($format) || is_int($format) ? (string) $format : null;
        if ($formatValue === null) {
            throw new \InvalidArgumentException('Invalid format value type');
        }

        if (FormatEnum::tryFrom($formatValue) === null) {
            throw new \InvalidArgumentException('Invalid format');
        }

        return [
            self::FORMAT => $formatValue,
        ];
    }

    public static function validate(string $key, ...$args): bool
    {
        return false;
    }

    public static function generateUrl(string $url, array $values): string
    {
        // -/format/:format
        $url .= '-/format/' . $values['format'] . '/';

        return $url;
    }
}
