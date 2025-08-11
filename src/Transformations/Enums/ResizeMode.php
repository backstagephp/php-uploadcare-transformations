<?php

namespace Backstage\UploadcareTransformations\Transformations\Enums;

enum ResizeMode: string
{
    case ON = 'on';
    case OFF = 'off';
    case FILL = 'fill';
}
