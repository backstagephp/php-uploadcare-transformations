<?php

namespace Backstage\UploadcareTransformations\Transformations;

use Backstage\UploadcareTransformations\Transformations\Interfaces\TransformationInterface;

class Blur implements TransformationInterface
{
    final public const STRENGTH = 'strength';
    final public const AMOUNT = 'amount';

    public static function transform(...$args): array
    {
        $strength = $args[0] ?? 0;
        $amount = $args[1] ?? 0;

        if (! self::validate('amount', $amount)) {
            throw new \InvalidArgumentException('Invalid amount');
        }

        return [
            self::STRENGTH => $strength,
            self::AMOUNT => $strength,
        ];
    }

    public static function validate(string $key, ...$args): bool
    {
        $value = (float) $args[0];

        if ($key === self::AMOUNT) {
            return $value >= -200 && $value <= 100;
        }

        return false;
    }

    public static function generateUrl(string $url, array $values): string
    {
        // Strength and amount might be null
        // -/blur/:strength/:amount
        $url .= '-/blur/' . $values['strength'] . '/' . $values['amount'] . '/';

        return $url;
    }
}
