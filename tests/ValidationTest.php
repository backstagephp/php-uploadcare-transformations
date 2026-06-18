<?php

it('can validate percentages', function () {
    $uuid = '12a3456b-c789-1234-1de2-3cfa83096e25';
    $transformation = uploadcare($uuid);

    $transformation->crop(width: 320, height: '50%', offsetX: 'center');
})->throws('Invalid height percentage');

it('can validate ratio', function () {
    $uuid = '12a3456b-c789-1234-1de2-3cfa83096e25';
    $transformation = uploadcare($uuid);

    $transformation->cropByObjects(tag: 'face', ratio: 1, width: 320, height: '50p', offsetX: 'center');
})->throws('Invalid ratio');

it('can validate basic color adjustment value', function () {
    $uuid = '12a3456b-c789-1234-1de2-3cfa83096e25';
    $transformation = uploadcare($uuid);

    $transformation->basicColorAdjustments(color: 'brightness', value: -150);
})->throws('Invalid value');

it('can validate offset enum', function () {
    $uuid = '12a3456b-c789-1234-1de2-3cfa83096e25';
    $transformation = uploadcare($uuid);

    $transformation->crop(width: 320, height: '50p', offsetX: 'invalid_offset');
})->throws('Invalid offset X');

it('can validate tag enum', function () {
    $uuid = '12a3456b-c789-1234-1de2-3cfa83096e25';
    $transformation = uploadcare($uuid);

    $transformation->cropByObjects(tag: 'invalid_tag', ratio: '1:1', width: 320, height: '50p', offsetX: 'center');
})->throws('Invalid tag');

it('can validate color enum', function () {
    $uuid = '12a3456b-c789-1234-1de2-3cfa83096e25';
    $transformation = uploadcare($uuid);

    $transformation->basicColorAdjustments(color: 'invalid_color', value: 50);
})->throws('Invalid color');

it('can validate color profile enum', function () {
    $uuid = '12a3456b-c789-1234-1de2-3cfa83096e25';
    $transformation = uploadcare($uuid);

    $transformation->convertToSRGB(profile: 'invalid_color_profile');
})->throws('Invalid color profile');

it('can validate crop type enum', function () {
    $uuid = '12a3456b-c789-1234-1de2-3cfa83096e25';
    $transformation = uploadcare($uuid);

    $transformation->smartCrop(width: 500, height: 500, type: 'invalid_crop_type');
})->throws('Invalid crop type');

it('can validate filter enum', function () {
    $uuid = '12a3456b-c789-1234-1de2-3cfa83096e25';
    $transformation = uploadcare($uuid);

    $transformation->filter(name: 'invalid_filter');
})->throws('Invalid filter');

it('can validate format enum', function () {
    $uuid = '12a3456b-c789-1234-1de2-3cfa83096e25';
    $transformation = uploadcare($uuid);

    $transformation->format(format: 'invalid_format');
})->throws('Invalid format');

it('can validate quality enum', function () {
    $uuid = '12a3456b-c789-1234-1de2-3cfa83096e25';
    $transformation = uploadcare($uuid);

    $transformation->quality(quality: 'invalid_quality');
})->throws('Invalid quality');

it('can validate resize mode enum', function () {
    $uuid = '12a3456b-c789-1234-1de2-3cfa83096e25';
    $transformation = uploadcare($uuid);

    $transformation->resize(width: 500, height: 500, stretch: false, mode: 'invalid_resize_mode');
})->throws('Invalid resize mode');

it('throws when iccProfileSizeThreshold is used without convertToSRGB', function () {
    $uuid = '12a3456b-c789-1234-1de2-3cfa83096e25';

    (string) uploadcare($uuid)->iccProfileSizeThreshold(10);
})->throws(\InvalidArgumentException::class, 'iccProfileSizeThreshold requires convertToSRGB');
