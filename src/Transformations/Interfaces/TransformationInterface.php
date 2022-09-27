<?php

namespace Vormkracht10\UploadcareTransformations\Transformations\Interfaces;

interface TransformationInterface
{
    /**
     * Validate the values.
     *
     * @param array<mixed>|string|int|bool ...$args
     * @return array<mixed>
     */
    public static function transform(array|string|int|bool  ...$args): array;

    /**
     * Validate and return the values.
     *
     * @param mixed ...$args
     * @return bool
     */
    public static function validate(string $key, mixed ...$args): bool;

    /**
     * Generate the url.
     *
     * @param array<mixed> $values
     * @return string
     */
    public static function generateUrl(string $url, array $values): string;
}
