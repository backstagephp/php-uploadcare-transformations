<?php

namespace Backstage\UploadcareTransformations\Transformations\Enums;

enum ColorProfile: string
{
    case FAST = 'fast';
    case ICC = 'icc';
    case KEEP_PROFILE = 'keep_profile';
}
