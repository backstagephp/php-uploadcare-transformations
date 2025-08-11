<?php

namespace Backstage\UploadcareTransformations\Transformations;

use Backstage\UploadcareTransformations\Transformations\Interfaces\TransformationInterface;

class AutoRotate implements TransformationInterface
{
    final public const AUTO_ROTATE = 'auto_rotate';

    public static function transform(...$args): array
    {
        $autoRotate = $args['auto_rotate'] ?? false;

        return [
            self::AUTO_ROTATE => $autoRotate,
        ];
    }

    public static function validate(string $key, ...$args): bool
    {
        return false;
    }

    public static function generateUrl(string $url, array $values): string
    {
        $value = ($values['auto_rotate']) ? 'yes' : 'no';

        // -/autorotate/:no/
        $url .= '-/autorotate/' . $value . '/';

        return $url;
    }
}
