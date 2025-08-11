<?php

namespace Backstage\UploadcareTransformations;

use Backstage\UploadcareTransformations\Transformations\TransformationsFinder;

class UploadcareTransformation extends Transformations implements \Stringable
{
    public function __construct(
        protected ?string $uuid = null,
        protected ?string $baseUrl = 'https://ucarecdn.com/',
        protected ?string $filename = null,
        protected ?string $proxyUrl = null,
    ) {
    }

    public function filename(string $filename): string
    {
        if (! is_null($this->filename)) {
            throw new \InvalidArgumentException('Filename already set');
        }

        $this->filename = $filename;

        return $this;
    }

    /** @param array<string, mixed> $transformations */
    public function transform(array $transformations = []): self
    {
        foreach ($transformations as $transformation => $parameters) {
            $this->{$transformation}(...$parameters);
        }

        return $this;
    }

    /**
     * Clone the current object to allow reuse for multiple transformations.
     */
    public function clone(): self
    {
        $clone = new self($this->uuid, $this->baseUrl, $this->filename);
        $clone->transformations = $this->transformations;

        return $clone;
    }

    /**
     * Apply all (chained) transformations to the given URL.
     */
    public function applyTransformations(string $url): string
    {
        $transformations = TransformationsFinder::for($this->transformations);

        foreach ($transformations as $transformation) {
            $className = get_class($transformation['class']);
            $url = $className::generateUrl($url, $transformation['values']);
        }

        return $url;
    }

    public function getUrl(): string
    {
        $url = $this->applyTransformations(rtrim($this->baseUrl . $this->uuid, '/') . '/');

        // Check if url contains one of the following strings: 'blur_region', 'enhance', 'filter', 'zoom_objects'
        // because these transformations won't work if they do not contain the preview transformation as well.
        // Check if url contains 'resize', 'scale_crop' or 'preview'. If not add, add 'preview' to the url.
        // By using 'preview' the image will not be changed and produce the biggest possible image.
        if (
            preg_match('~\/(blur_region|enhance|filter|rasterize|zoom_objects)\/~', $url) &&
            ! preg_match('~\/(preview|scale_crop|smart_resize|resize)\/~', $url)
        ) {
            $url .= '-/preview/';
        }

        if (! str_ends_with($url, '/')) {
            $url .= '/';
        }

        if ($this->filename) {
            $url = rtrim($url, '/') . '/' . $this->filename;
        }

        return $url;
    }

    public function __toString(): string
    {
        return $this->getUrl();
    }
}
