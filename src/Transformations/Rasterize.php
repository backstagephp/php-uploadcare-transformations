<?php

namespace Vormkracht10\UploadcareTransformations\Transformations;

use Vormkracht10\UploadcareTransformations\Transformations\Interfaces\TransformationInterface;

class Rasterize implements TransformationInterface
{
    public static function transform(...$args): array
    {
        return [];
    }

    public static function validate(string $key, ...$args): bool
    {
        return false;
    }

    public static function generateUrl(string $url, array $values): string
    {
        // -/rasterize
        $url .= '-/rasterize/';

        return $url;
    }
}
