<?php

namespace Backstage\UploadcareTransformations\Transformations;

class TransformationsFinder
{
    final public const AUTO_ROTATE = 'auto_rotate';
    final public const BASIC_COLOR_ADJUSTMENTS = 'basic_color_adjustments';
    final public const BLUR = 'blur';
    final public const BLUR_FACES = 'blur_faces';
    final public const BLUR_REGION = 'blur_region';
    final public const CONVERT_TO_SRGB = 'convert_to_srgb';
    final public const CROP = 'crop';
    final public const CROP_BY_OBJECTS = 'crop_by_objects';
    final public const CROP_BY_RATIO = 'crop_by_ratio';
    final public const ENHANCE = 'enhance';
    final public const FILTER = 'filter';
    final public const FLIP = 'flip';
    final public const FORMAT = 'format';
    final public const GRAYSCALE = 'grayscale';
    final public const ICC_PROFILE_SIZE_THRESHOLD = 'icc_profile_size_threshold';
    final public const INVERT = 'invert';
    final public const MIRROR = 'mirror';
    final public const OVERLAY = 'overlay';
    final public const PREVIEW = 'preview';
    final public const PROGRESSIVE = 'progressive';
    final public const QUALITY = 'quality';
    final public const RASTERIZE = 'rasterize';
    final public const RESIZE = 'resize';
    final public const ROTATE = 'rotate';
    final public const SCALE_CROP = 'scale_crop';
    final public const SET_FILL = 'set_fill';
    final public const SHARPEN = 'sharpen';
    final public const STRIP_META = 'strip_meta';
    final public const SMART_CROP = 'smart_crop';
    final public const SMART_RESIZE = 'smart_resize';
    final public const ZOOM_OBJECTS = 'zoom_objects';

    /**
     * Get transformation class
     */
    public static function getTransformation(string $key): string|null
    {
        $transformations = [
            self::AUTO_ROTATE => AutoRotate::class,
            self::BASIC_COLOR_ADJUSTMENTS => BasicColorAdjustments::class,
            self::BLUR => Blur::class,
            self::BLUR_FACES => BlurFaces::class,
            self::BLUR_REGION => BlurRegion::class,
            self::CONVERT_TO_SRGB => ConvertToSRGB::class,
            self::CROP => Crop::class,
            self::CROP_BY_OBJECTS => CropByObjects::class,
            self::CROP_BY_RATIO => CropByRatio::class,
            self::ENHANCE => Enhance::class,
            self::FILTER => Filter::class,
            self::FLIP => Flip::class,
            self::FORMAT => Format::class,
            self::GRAYSCALE => Grayscale::class,
            self::ICC_PROFILE_SIZE_THRESHOLD => ICCProfileSizeThreshold::class,
            self::INVERT => Invert::class,
            self::MIRROR => Mirror::class,
            self::OVERLAY => Overlay::class,
            self::PREVIEW => Preview::class,
            self::PROGRESSIVE => Progressive::class,
            self::QUALITY => Quality::class,
            self::RASTERIZE => Rasterize::class,
            self::RESIZE => Resize::class,
            self::ROTATE => Rotate::class,
            self::SCALE_CROP => ScaleCrop::class,
            self::SET_FILL => SetFill::class,
            self::STRIP_META => StripMeta::class,
            self::SHARPEN => Sharpen::class,
            self::SMART_CROP => SmartCrop::class,
            self::SMART_RESIZE => SmartResize::class,
            self::ZOOM_OBJECTS => ZoomObjects::class,
        ];

        return $transformations[$key] ?? null;
    }

    /**
     * Get all transformations
     *
     * @param array<array<array<string>>> $transformations
     * @return array<array{class: \Backstage\UploadcareTransformations\Transformations\Interfaces\TransformationInterface, values: array<array<string>>}>
     */
    public static function for(array $transformations): array
    {
        $classes = [];

        $keys = array_keys($transformations);

        foreach ($keys as $transformation) {
            // Create a new class instance from the transformation
            $class = self::getTransformation($transformation);
            $class = new $class();

            // We need to check if ICCProfileSizeThreshold is used because it is a special case.
            // This is because the URL transformation is a part of the ConvertToSRGB transformation.
            if ($transformation === self::ICC_PROFILE_SIZE_THRESHOLD) {
                $classes[self::CONVERT_TO_SRGB] = [
                    'class' => new ConvertToSRGB(),
                    'values' => [
                        'profile' => $transformations[self::CONVERT_TO_SRGB]['profile'],
                        'size' => $transformations[$transformation]['number'],
                    ],
                ];

                continue;
            }

            $classes[$transformation] = [
                'class' => $class,
                'values' => $transformations[$transformation],
            ];
        }

        return $classes;
    }
}
