<?php

namespace Backstage\UploadcareTransformations\Transformations;

use Backstage\UploadcareTransformations\Transformations\Interfaces\TransformationInterface;

class SmartResize implements TransformationInterface
{
    final public const WIDTH = 'width';
    final public const HEIGHT = 'height';

    public static function transform(...$args): array
    {
        $width = $args[0];
        $height = $args[1];

        return [
            self::WIDTH => $width,
            self::HEIGHT => $height,

        ];
    }

    public static function validate(string $key, ...$args): bool
    {
        return false;
    }

    public static function generateUrl(string $url, array $values): string
    {
        // -/smart_resize/:dimensions/
        $url .= '-/smart_resize/' . $values['width'] . 'x' . $values['height'] . '/';

        return $url;
    }
}
