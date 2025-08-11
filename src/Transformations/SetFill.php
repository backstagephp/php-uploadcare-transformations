<?php

namespace Backstage\UploadcareTransformations\Transformations;

use Backstage\UploadcareTransformations\Transformations\Interfaces\TransformationInterface;

class SetFill implements TransformationInterface
{
    final public const COLOR = 'color';

    public static function transform(...$args): array
    {
        $color = $args[0];

        if (! self::validate('color', $color)) {
            throw new \InvalidArgumentException('Color must be a valid hex color (without #)');
        }

        return [
            self::COLOR => $color,
        ];
    }

    public static function validate(string $key, ...$args): bool
    {
        $value = $args[0];

        return $key === self::COLOR && preg_match('/^[a-f0-9]{6}$/i', (string) $value);
    }

    public static function generateUrl(string $url, array $values): string
    {
        // -/set_fill/:color
        $url .= '-/setfill/' . $values['color'] . '/';

        return $url;
    }
}
