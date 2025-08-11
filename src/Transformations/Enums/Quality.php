<?php

namespace Backstage\UploadcareTransformations\Transformations\Enums;

enum Quality: string
{
    case SMART = 'smart';
    case SMARTRETINA = 'smart_retina';
    case NORMAL = 'normal';
    case BETTER = 'better';
    case BEST = 'best';
    case LIGHTER = 'lighter';
    case LIGHTEST = 'lightest';
}
