<?php

namespace Backstage\UploadcareTransformations\Transformations;

use Backstage\UploadcareTransformations\Traits\Validations;
use Backstage\UploadcareTransformations\Transformations\Interfaces\TransformationInterface;

class StripMeta implements TransformationInterface
{
    use Validations;

    final public const STRIP_META = 'strip_meta';

    public static function transform(...$args): array
    {
        $value = $args[0] ?? 'all';

        if (! self::validate('strip_meta', $value)) {
            throw new \InvalidArgumentException('Invalid strip meta value. Must be one of: all, none, sensitive');
        }

        return [
            self::STRIP_META => $value,
        ];
    }

    public static function validate(string $key, ...$args): bool
    {
        $value = $args[0];

        if ($key === self::STRIP_META) {
            return in_array($value, ['all', 'none', 'sensitive'], true);
        }

        return false;
    }

    public static function generateUrl(string $url, array $values): string
    {
        // -/strip_meta/:value/
        $url .= '-/strip_meta/' . $values['strip_meta'] . '/';

        return $url;
    }
}
