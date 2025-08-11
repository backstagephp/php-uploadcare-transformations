<?php

namespace Backstage\UploadcareTransformations\Transformations;

use Backstage\UploadcareTransformations\Traits\Validations;
use Backstage\UploadcareTransformations\Transformations\Interfaces\TransformationInterface;

class BlurRegion implements TransformationInterface
{
    use Validations;

    final public const DIMENSION_X = 'dimension_x';
    final public const DIMENSION_Y = 'dimension_y';
    final public const COORDINATE_X = 'coordinate_x';
    final public const COORDINATE_Y = 'coordinate_y';
    final public const STRENGTH = 'strength';

    public static function transform(...$args): array
    {
        $dimensionX = $args[0];
        $dimensionY = $args[1];
        $coordinateX = $args[2];
        $coordinateY = $args[3];
        $strength = $args[4] ?? null;

        if (is_string($dimensionX) && ! self::validate('dimension_x', $dimensionX)) {
            throw new \InvalidArgumentException('Invalid dimension x');
        }

        if (is_string($dimensionY) && ! self::validate('dimension_y', $dimensionY)) {
            throw new \InvalidArgumentException('Invalid dimension y');
        }

        if (is_string($coordinateX) && ! self::validate('coordinate_x', $coordinateX)) {
            throw new \InvalidArgumentException('Invalid coordinate x');
        }

        if (is_string($coordinateY) && ! self::validate('coordinate_y', $coordinateY)) {
            throw new \InvalidArgumentException('Invalid coordinate y');
        }

        return [
            self::DIMENSION_X => $dimensionX,
            self::DIMENSION_Y => $dimensionY,
            self::COORDINATE_X => $coordinateX,
            self::COORDINATE_Y => $coordinateY,
            self::STRENGTH => $strength,
        ];
    }

    public static function validate(string $key, ...$args): bool
    {
        $value = $args[0];

        if ($key === self::DIMENSION_X ||
            $key === self::DIMENSION_Y ||
            $key === self::COORDINATE_X ||
            $key === self::COORDINATE_Y) {
            return self::isValidPercentage($value);
        }

        return false;
    }

    public static function generateUrl(string $url, array $values): string
    {
        // -/blur_region/:two_dimensions/:two_coords/:strength/
        $url .= '-/blur_region/' . $values['dimension_x'] . 'x' . $values['dimension_y'] . '/' . $values['coordinate_x'] . ',' . $values['coordinate_y'] . '/' . $values['strength'] . '/';

        return $url;
    }
}
