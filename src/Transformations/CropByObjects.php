<?php

namespace Backstage\UploadcareTransformations\Transformations;

use Backstage\UploadcareTransformations\Traits\Validations;
use Backstage\UploadcareTransformations\Transformations\Enums\Offset;
use Backstage\UploadcareTransformations\Transformations\Enums\Tag;
use Backstage\UploadcareTransformations\Transformations\Interfaces\TransformationInterface;

class CropByObjects implements TransformationInterface
{
    use Validations;

    final public const TAG = 'tag';
    final public const RATIO = 'ratio';
    final public const WIDTH = 'width';
    final public const HEIGHT = 'height';
    final public const OFFSET_X = 'offset_x';
    final public const OFFSET_Y = 'offset_y';
    final public const ALIGN = 'align';

    public static function transform(...$args): array
    {
        $tag = $args[0];
        $ratio = $args[1] ?? null;
        $width = $args[2] ?? null;
        $height = $args[3] ?? null;
        $offsetX = $args[4] ?? null;
        $offsetY = $args[5] ?? null;

        $tagValue = is_string($tag) || is_int($tag) ? (string) $tag : null;
        if ($tagValue === null) {
            throw new \InvalidArgumentException('Invalid tag value type');
        }

        if (Tag::tryFrom($tagValue) === null) {
            throw new \InvalidArgumentException('Invalid tag');
        }

        if ($ratio && ! self::validate('ratio', $ratio)) {
            throw new \InvalidArgumentException('Invalid ratio');
        }

        if ($offsetX && ! self::validate('offset_x', $offsetX)) {
            throw new \InvalidArgumentException('Invalid offset X');
        }

        if ($offsetY && ! self::validate('offset_y', $offsetY)) {
            throw new \InvalidArgumentException('Invalid offset Y');
        }

        if (! $offsetY) {
            return [
                self::TAG => $tag,
                self::RATIO => $ratio,
                self::WIDTH => $width,
                self::HEIGHT => $height,
                self::ALIGN => $offsetX,
            ];
        }

        return [
            self::TAG => $tag,
            self::RATIO => $ratio,
            self::WIDTH => $width,
            self::HEIGHT => $height,
            self::OFFSET_X => $offsetX,
            self::OFFSET_Y => $offsetY,
        ];
    }

    public static function validate(string $key, ...$args): bool
    {
        $value = $args[0];

        if ($key === self::RATIO) {
            return (bool) preg_match('/^\d+:\d+$/', (string) $value);
        }

        if ($key === self::OFFSET_X) {
            return Offset::tryFrom($value) || self::isValidPercentage($value);
        }

        if ($key === self::OFFSET_Y || $key === self::WIDTH || $key === self::HEIGHT) {
            return self::isValidPercentage($value);
        }

        return false;
    }

    public static function generateUrl(string $url, array $values): string
    {
        if (isset($values['width']) && isset($values['height']) && isset($values['offset_x']) && isset($values['offset_y'])) {
            // -/crop/:tag/:dimensions/:alignment/
            $url .= '-/crop/' . $values['tag'] . '/' . $values['width'] . 'x' . $values['height'] . '/' . $values['offset_x'] . ',' . $values['offset_y'] . '/';
        } elseif (isset($values['ratio']) && isset($values['offset_x']) && isset($values['offset_y'])) {
            // -/crop/:tag/:ratio/:alignment/
            $url .= '-/crop/' . $values['tag'] . '/' . $values['ratio'] . '/' . $values['offset_x'] . ',' . $values['offset_y'] . '/';
        } elseif (isset($values['ratio']) && isset($values['align'])) {
            // -/crop/:tag/:ratio/:alignment/
            $url .= '-/crop/' . $values['tag'] . '/' . $values['ratio'] . '/' . $values['align'] . '/';
        } elseif (isset($values['ratio'])) {
            // -/crop/:tag/:ratio/
            $url .= '-/crop/' . $values['tag'] . '/' . $values['ratio'] . '/';
        } elseif (isset($values['width']) && isset($values['height']) && isset($values['align'])) {
            // -/crop/:tag/:dimensions/:alignment/
            $url .= '-/crop/' . $values['tag'] . '/' . $values['width'] . 'x' . $values['height'] . '/' . $values['align'] . '/';
        } elseif (isset($values['width']) && isset($values['height'])) {
            // -/crop/:tag/:dimensions/
            $url .= '-/crop/' . $values['tag'] . '/' . $values['width'] . 'x' . $values['height'] . '/';
        } else {
            // -/crop/:tag/
            $url .= '-/crop/' . $values['tag'] . '/';
        }

        return $url;
    }
}
