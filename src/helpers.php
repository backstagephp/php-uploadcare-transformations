<?php

use Backstage\UploadcareTransformations\UploadcareTransformation;

if (! function_exists('uploadcare')) {
    function uploadcare(string $uuidOrUrl, ?string $cdnOrProxyUrl = null): UploadcareTransformation
    {
        // proxy url
        if (strpos($uuidOrUrl, 'http') === 0) {
            if (is_laravel()) {
                /** @phpstan-ignore-next-line */
                $proxyUrl = config('services.uploadcare.proxy_url');
            }

            return new UploadcareTransformation(baseUrl: $cdnOrProxyUrl ?? $proxyUrl ?? 'https://example.ucr.io/', filename: $uuidOrUrl);
        }

        // without proxy url
        if (is_laravel()) {
            /** @phpstan-ignore-next-line */
            $cdnUrl = config('services.uploadcare.cdn_url');
        }

        return new UploadcareTransformation(uuid: $uuidOrUrl, baseUrl: $cdnOrProxyUrl ?? $cdnUrl ?? 'https://ucarecdn.com/');
    }
}

if (! function_exists('uc')) {
    function uc(string $uuidOrUrl, ?string $cdnOrProxyUrl = null): UploadcareTransformation
    {
        return uploadcare($uuidOrUrl, $cdnOrProxyUrl);
    }
}

if (! function_exists('uc_clone')) {
    function uc_clone(UploadcareTransformation $transformation): UploadcareTransformation
    {
        return $transformation->clone();
    }
}

if (! function_exists('is_laravel')) {
    function is_laravel(): bool
    {
        return defined('LARAVEL_START') &&
            function_exists('config');
    }
}
