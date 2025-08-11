<?php

namespace Backstage\UploadcareTransformations\Transformations;

use Backstage\UploadcareTransformations\Transformations\Interfaces\TransformationInterface;

class ZoomObjects implements TransformationInterface
{
    final public const ZOOM = 'zoom';

    public static function transform(...$args): array
    {
        $zoom = $args[0];

        if (! self::validate('zoom', [$zoom])) {
            throw new \InvalidArgumentException('Invalid zoom');
        }

        return [
            self::ZOOM => $zoom,
        ];
    }

    public static function validate(string $key, ...$args): bool
    {
        $zoom = (float) $args[0];

        if ($key === self::ZOOM) {
            return $zoom >= 0 && $zoom <= 100;
        }

        return false;
    }

    public static function generateUrl(string $url, array $values): string
    {
        // -/zoom_objects/:zoom
        $url .= '-/zoom_objects/' . $values['zoom'] . '/';

        return $url;
    }
}
