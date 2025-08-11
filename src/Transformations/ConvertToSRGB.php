<?php

namespace Backstage\UploadcareTransformations\Transformations;

use Backstage\UploadcareTransformations\Transformations\Enums\ColorProfile;
use Backstage\UploadcareTransformations\Transformations\Interfaces\TransformationInterface;

class ConvertToSRGB implements TransformationInterface
{
    final public const PROFILE = 'profile';

    public static function transform(...$args): array
    {
        $profileValue = is_string($args[0]) || is_int($args[0]) ? (string) $args[0] : null;
        if ($profileValue === null) {
            throw new \InvalidArgumentException('Invalid profile value type');
        }

        if (ColorProfile::tryFrom($profileValue) === null) {
            throw new \InvalidArgumentException('Invalid color profile');
        }

        return [
            self::PROFILE => $profileValue,
        ];
    }

    public static function validate(string $key, ...$args): bool
    {
        return false;
    }

    public static function generateUrl(string $url, array $values): string
    {
        if (isset($values['size'])) {
            // -/max_icc_size/:number
            $url .= '-/max_icc_size/' . $values['size'] . '/';
        }

        // -/srgb/:profile
        $url .= '-/srgb/' . $values['profile'] . '/';

        return $url;
    }
}
