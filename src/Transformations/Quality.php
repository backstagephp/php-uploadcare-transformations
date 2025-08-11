<?php

namespace Backstage\UploadcareTransformations\Transformations;

use Backstage\UploadcareTransformations\Transformations\Enums\Quality as QualityEnum;
use Backstage\UploadcareTransformations\Transformations\Interfaces\TransformationInterface;

class Quality implements TransformationInterface
{
    final public const QUALITY = 'quality';

    public static function transform(...$args): array
    {
        $qualityValue = is_string($args[0]) || is_int($args[0]) ? (string) $args[0] : null;
        if ($qualityValue === null) {
            throw new \InvalidArgumentException('Invalid quality value type');
        }

        $quality = QualityEnum::tryFrom($qualityValue);

        if (! $quality instanceof \Backstage\UploadcareTransformations\Transformations\Enums\Quality) {
            throw new \InvalidArgumentException('Invalid quality');
        }

        return [
            self::QUALITY => $quality->value,
        ];
    }

    public static function validate(string $key, ...$args): bool
    {
        return false;
    }

    public static function generateUrl(string $url, array $values): string
    {
        // -/quality/:quality
        $url .= '-/quality/' . $values['quality'] . '/';

        return $url;
    }
}
