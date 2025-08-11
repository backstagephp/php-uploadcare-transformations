<?php

namespace Backstage\UploadcareTransformations\Transformations\Enums;

enum CropType: string
{
    case SMART = 'smart';
    case SMART_FACES_OBJECTS = 'smart_faces_objects';
    case SMART_FACES_POINTS = 'smart_faces_points';
    case SMART_OBJECTS_FACES_POINTS = 'smart_objects_faces_points';
    case SMART_OBJECTS_FACES = 'smart_objects_faces';
    case SMART_OBJECTS_POINTS = 'smart_objects_points';
    case SMART_POINTS = 'smart_points';
    case SMART_OBJECTS = 'smart_objects';
    case SMART_FACES = 'smart_faces';
}
