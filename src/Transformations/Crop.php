<?php

namespace Backstage\UploadcareTransformations\Transformations;

use Backstage\UploadcareTransformations\Traits\Validations;
use Backstage\UploadcareTransformations\Transformations\Enums\Offset;
use Backstage\UploadcareTransformations\Transformations\Interfaces\TransformationInterface;

class Crop implements TransformationInterface
{
    use Validations;

    final public const WIDTH = 'width';
    final public const HEIGHT = 'height';
    final public const OFFSET_X = 'offset_x';
    final public const OFFSET_Y = 'offset_y';
    final public const ALIGN = 'align';

    public static function transform(...$args): array
    {
        $width = $args[0];
        $height = $args[1];
        $offsetX = $args[2] ?? null;
        $offsetY = $args[3] ?? null;

        if (is_string($width) && ! self::validate('width', $width)) {
            throw new \InvalidArgumentException('Invalid width percentage');
        }

        if (is_string($height) && ! self::validate('height', $height)) {
            throw new \InvalidArgumentException('Invalid height percentage');
        }

        if ($offsetX && ! self::validate('offset_x', $offsetX)) {
            throw new \InvalidArgumentException('Invalid offset X');
        }

        if ($offsetY && ! self::validate('offset_y', $offsetY)) {
            throw new \InvalidArgumentException('Invalid offset Y');
        }

        if (isset($offsetX) && (is_string($offsetX) || is_int($offsetX)) && Offset::tryFrom((string) $offsetX)) {
            return [
                self::WIDTH => $width,
                self::HEIGHT => $height,
                self::ALIGN => $offsetX,
            ];
        }

        return [
            self::WIDTH => $width,
            self::HEIGHT => $height,
            self::OFFSET_X => $offsetX,
            self::OFFSET_Y => $offsetY,
        ];
    }

    public static function validate(string $key, ...$args): bool
    {
        $value = $args[0];

        if ($key === self::OFFSET_X && is_string($value)) {
            return Offset::tryFrom($value) || self::isValidPercentage($value);
        }

        if ($key === self::OFFSET_X && is_int($value) || $key === self::OFFSET_Y && is_int($value)) {
            return $value >= 0;
        }

        if ($key === self::OFFSET_Y && is_string($value) || $key === self::WIDTH || $key === self::HEIGHT) {
            return self::isValidPercentage($value);
        }

        return false;
    }

    public static function generateUrl(string $url, array $values): string
    {
        if (isset($values['align'])) {
            // -/crop/:dimensions/:alignment/
            $url .= '-/crop/' . $values['width'] . 'x' . $values['height'] . '/' . $values['align'] . '/';
        } elseif (isset($values['offset_x']) && isset($values['offset_y'])) {
            // -/crop/:dimensions/:alignment/
            $url .= '-/crop/' . $values['width'] . 'x' . $values['height'] . '/' . $values['offset_x'] . ',' . $values['offset_y'] . '/';
        } else {
            // -/crop/:dimensions/:alignment/
            $url .= '-/crop/' . $values['width'] . 'x' . $values['height'] . '/';
        }

        return $url;
    }
}
