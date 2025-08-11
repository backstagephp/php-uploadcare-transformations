<?php

namespace Backstage\UploadcareTransformations\Transformations;

use Backstage\UploadcareTransformations\Traits\Validations;
use Backstage\UploadcareTransformations\Transformations\Enums\CropType;
use Backstage\UploadcareTransformations\Transformations\Enums\Offset;
use Backstage\UploadcareTransformations\Transformations\Interfaces\TransformationInterface;

class SmartCrop implements TransformationInterface
{
    use Validations;

    final public const WIDTH = 'width';
    final public const HEIGHT = 'height';
    final public const TYPE = 'type';
    final public const OFFSET_X = 'offset_x';
    final public const OFFSET_Y = 'offset_y';
    final public const ALIGN = 'align';

    public static function transform(...$args): array
    {
        $width = $args[0];
        $height = $args[1];
        $typeValue = is_string($args[2]) || is_int($args[2]) ? (string) $args[2] : null;
        if ($typeValue === null) {
            throw new \InvalidArgumentException('Invalid crop type value type');
        }

        $type = CropType::tryFrom($typeValue);
        $offsetX = $args[3] ?? null;
        $offsetY = $args[4] ?? null;

        if (! $type instanceof \Backstage\UploadcareTransformations\Transformations\Enums\CropType) {
            throw new \InvalidArgumentException('Invalid crop type');
        }

        if ($offsetX && ! self::validate('offset_x', $offsetX)) {
            throw new \InvalidArgumentException('Invalid offset X');
        }

        if ($offsetY && ! self::validate('offset_y', $offsetY)) {
            throw new \InvalidArgumentException('Invalid offset Y');
        }

        if (! $offsetY) {
            return [
                self::WIDTH => $width,
                self::HEIGHT => $height,
                self::TYPE => $type->value,
                self::ALIGN => $offsetX,
            ];
        }

        return [
            self::WIDTH => $width,
            self::HEIGHT => $height,
            self::TYPE => $type->value,
            self::OFFSET_X => $offsetX,
            self::OFFSET_Y => $offsetY,
        ];
    }

    public static function validate(string $key, ...$args): bool
    {
        $value = $args[0];

        if ($key === self::OFFSET_X) {
            return Offset::tryFrom($value) || self::isValidPercentage($value);
        }

        if ($key === self::OFFSET_Y) {
            return self::isValidPercentage($value);
        }

        if ($key === self::TYPE) {
            return CropType::tryFrom($value) !== null;
        }

        return false;
    }

    public static function generateUrl(string $url, array $values): string
    {
        if (! isset($values['align']) && ! isset($values['offset_x']) && ! isset($values['offset_y'])) {
            // -/scale_crop/:dimensions/:type
            $url .= '-/scale_crop/' . $values['width'] . 'x' . $values['height'] . '/' . $values['type'] . '/';
        } elseif (isset($values['align'])) {
            // -/scale_crop/:dimensions/:type/:alignment
            $url .= '-/scale_crop/' . $values['width'] . 'x' . $values['height'] . '/' . $values['type'] . '/' . $values['align'] . '/';
        } elseif (isset($values['offset_x']) && isset($values['offset_y'])) {
            // -/scale_crop/:dimensions/:type/:alignment
            $url .= '-/scale_crop/' . $values['width'] . 'x' . $values['height'] . '/' . $values['type'] . '/' . $values['offset_x'] . ',' . $values['offset_y'] . '/';
        }

        return $url;
    }
}
