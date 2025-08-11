<?php

namespace Backstage\UploadcareTransformations\Transformations\Enums;

enum Color: string
{
    case BRIGHTNESS = 'brightness';
    case EXPOSURE = 'exposure';
    case GAMMA = 'gamma';
    case CONTRAST = 'contrast';
    case SATURATION = 'saturation';
    case VIBRANCE = 'vibrance';
    case WARMTH = 'warmth';
}
