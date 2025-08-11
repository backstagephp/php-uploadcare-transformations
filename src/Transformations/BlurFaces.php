<?php

namespace Backstage\UploadcareTransformations\Transformations;

use Backstage\UploadcareTransformations\Transformations\Interfaces\TransformationInterface;

class BlurFaces implements TransformationInterface
{
    final public const STRENGTH = 'strength';

    public static function transform(...$args): array
    {
        $strength = $args[0] ?? null;

        return [
            self::STRENGTH => $strength,
        ];
    }

    public static function validate(string $key, ...$args): bool
    {
        return false;
    }

    public static function generateUrl(string $url, array $values): string
    {
        // -/blur_region/faces/:strength/
        $url .= '-/blur_region/faces/' . $values['strength'] . '/';

        return $url;
    }
}
