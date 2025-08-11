<?php

namespace Backstage\UploadcareTransformations\Transformations;

use Backstage\UploadcareTransformations\Transformations\Interfaces\TransformationInterface;

class Rotate implements TransformationInterface
{
    final public const ANGLE = 'angle';

    public static function transform(...$args): array
    {
        $angle = $args[0];

        if (! self::validate('angle', $angle)) {
            throw new \InvalidArgumentException('Invalid angle argument');
        }

        return [
            self::ANGLE => $angle,
        ];
    }

    public static function validate(string $key, ...$args): bool
    {
        if ($key === self::ANGLE) {
            return is_int($args[0]) && $args[0] % 90 === 0;
        }

        return false;
    }

    public static function generateUrl(string $url, array $values): string
    {
        // -/rotate/:angle/
        $url .= '-/rotate/' . $values['angle'] . '/';

        return $url;
    }
}
