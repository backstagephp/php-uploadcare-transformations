<?php

namespace Backstage\UploadcareTransformations\Transformations;

use Backstage\UploadcareTransformations\Transformations\Enums\Filter as FilterEnum;
use Backstage\UploadcareTransformations\Transformations\Interfaces\TransformationInterface;

class Filter implements TransformationInterface
{
    final public const NAME = 'name';
    final public const AMOUNT = 'amount';

    public static function transform(...$args): array
    {
        $name = $args[0];
        $amount = $args[1];

        $nameValue = is_string($name) || is_int($name) ? (string) $name : null;
        if ($nameValue === null) {
            throw new \InvalidArgumentException('Invalid filter name type');
        }

        if (FilterEnum::tryFrom($nameValue) === null) {
            throw new \InvalidArgumentException('Invalid filter');
        }

        if (! self::validate('amount', $amount)) {
            throw new \InvalidArgumentException('Invalid amount');
        }

        return [
            self::NAME => $name,
            self::AMOUNT => $amount,
        ];
    }

    public static function validate(string $key, ...$args): bool
    {
        $value = (float) $args[0];

        if ($key === self::AMOUNT) {
            return $value >= -100 && $value <= 200;
        }

        return false;
    }

    public static function generateUrl(string $url, array $values): string
    {
        // -/filter/:name/:amount/
        $url .= '-/filter/' . $values['name'] . '/' . $values['amount'] . '/';

        return $url;
    }
}
