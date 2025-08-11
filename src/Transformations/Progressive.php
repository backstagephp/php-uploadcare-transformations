<?php

namespace Backstage\UploadcareTransformations\Transformations;

use Backstage\UploadcareTransformations\Transformations\Interfaces\TransformationInterface;

class Progressive implements TransformationInterface
{
    final public const PROGRESSIVE = 'progressive';

    public static function transform(...$args): array
    {
        $progressive = $args[0];

        return [
            self::PROGRESSIVE => $progressive,
        ];
    }

    public static function validate(string $key, ...$args): bool
    {
        return false;
    }

    public static function generateUrl(string $url, array $values): string
    {
        $value = ($values['progressive']) ? 'yes' : 'no';

        // -/progressive/:value
        $url .= '-/progressive/' . $value . '/';

        return $url;
    }
}
