<?php

namespace Backstage\UploadcareTransformations\Transformations;

use Backstage\UploadcareTransformations\Transformations\Interfaces\TransformationInterface;

class ICCProfileSizeThreshold implements TransformationInterface
{
    final public const NUMBER = 'number';

    public static function transform(...$args): array
    {
        $number = $args[0];

        return [
            self::NUMBER => $number,
        ];
    }

    public static function validate(string $key, ...$args): bool
    {
        return false;
    }

    public static function generateUrl(string $url, array $values): string
    {
        // We don't have to do anything here as it is already part of of the ConvertToSRGB transformation.
        return $url;
    }
}
