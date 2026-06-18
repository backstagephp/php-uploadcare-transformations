<?php

it('can transform using array input', function () {
    $uuid = '12a3456b-c789-1234-1de2-3cfa83096e25';
    $uc = uploadcare($uuid);

    // transform using array input
    $url = (string) $uc->transform([
        'resize' => ['width' => 100, 'height' => 200],
    ]);

    expect($url)->toBe('https://ucarecdn.com/12a3456b-c789-1234-1de2-3cfa83096e25/-/resize/100x200/');

    // combine transform function with chaining
    $url = (string) $uc->transform([
        'resize' => ['width' => 200, 'height' => 200],
    ])
    ->crop(300, 400);

    expect($url)->toBe('https://ucarecdn.com/12a3456b-c789-1234-1de2-3cfa83096e25/-/resize/200x200/-/crop/300x400/');
});

it('can crop by objects', function () {
    $uuid = '12a3456b-c789-1234-1de2-3cfa83096e25';
    $transformation = uploadcare($uuid);

    // -/crop/:tag/:dimensions/:alignment/
    $url = (string) $transformation->cropByObjects(tag: 'face', ratio: null, width: 200, height: 200, offsetX: 'center');
    expect($url)->toBe('https://ucarecdn.com/12a3456b-c789-1234-1de2-3cfa83096e25/-/crop/face/200x200/center/');

    // -/crop/:tag/:dimensions/:alignment/
    $url = (string) $transformation->cropByObjects(tag: 'face', ratio: null, width: 200, height: 200, offsetX: '50p', offsetY: '50p');
    expect($url)->toBe('https://ucarecdn.com/12a3456b-c789-1234-1de2-3cfa83096e25/-/crop/face/200x200/50p,50p/');

    // -/crop/:tag/:ratio/:alignment/
    $url = (string) $transformation->cropByObjects(tag: 'face', ratio: '1:1', width: null, height: null, offsetX: '50p', offsetY: '50p');
    expect($url)->toBe('https://ucarecdn.com/12a3456b-c789-1234-1de2-3cfa83096e25/-/crop/face/1:1/50p,50p/');

    // -/crop/:tag/:dimensions/
    $url = (string) $transformation->cropByObjects(tag: 'face', ratio: null, width: 200, height: 200);
    expect($url)->toBe('https://ucarecdn.com/12a3456b-c789-1234-1de2-3cfa83096e25/-/crop/face/200x200/');

    // -/crop/:tag/:ratio/:alignment/
    $url = (string) $transformation->cropByObjects(tag: 'face', ratio: '1:1', width: null, height: null, offsetX: 'center');
    expect($url)->toBe('https://ucarecdn.com/12a3456b-c789-1234-1de2-3cfa83096e25/-/crop/face/1:1/center/');

    // -/crop/:tag/:ratio/
    $url = (string) $transformation->cropByObjects(tag: 'face', ratio: '1:1');
    expect($url)->toBe('https://ucarecdn.com/12a3456b-c789-1234-1de2-3cfa83096e25/-/crop/face/1:1/');

    // -/crop/:tag/
    $url = (string) $transformation->cropByObjects(tag: 'face');
    expect($url)->toBe('https://ucarecdn.com/12a3456b-c789-1234-1de2-3cfa83096e25/-/crop/face/');
});

it('can crop', function () {
    $uuid = '12a3456b-c789-1234-1de2-3cfa83096e25';
    $transformation = uploadcare($uuid);

    // -/crop/:dimensions/:alignment/
    $url = (string) $transformation->crop(width: 200, height: 200, offsetX: 'center');
    expect($url)->toBe('https://ucarecdn.com/12a3456b-c789-1234-1de2-3cfa83096e25/-/crop/200x200/center/');

    // -/crop/:dimensions/:alignment/
    $url = (string) $transformation->crop(width: 200, height: 200, offsetX: '50p', offsetY: '50p');
    expect($url)->toBe('https://ucarecdn.com/12a3456b-c789-1234-1de2-3cfa83096e25/-/crop/200x200/50p,50p/');

    // -/crop/:dimensions/:alignment/
    $url = (string) $transformation->crop(width: 200, height: 200);
    expect($url)->toBe('https://ucarecdn.com/12a3456b-c789-1234-1de2-3cfa83096e25/-/crop/200x200/');
});

it('can crop by ratio', function () {
    $uuid = '12a3456b-c789-1234-1de2-3cfa83096e25';
    $transformation = uploadcare($uuid);

    // -/crop/:ratio/:alignment/
    $url = (string) $transformation->cropByRatio(ratio: '1:1', offsetX: 'center');
    expect($url)->toBe('https://ucarecdn.com/12a3456b-c789-1234-1de2-3cfa83096e25/-/crop/1:1/center/');

    // -/crop/:ratio/:alignment/
    $url = (string) $transformation->cropByRatio(ratio: '1:1', offsetX: '50p', offsetY: '50p');
    expect($url)->toBe('https://ucarecdn.com/12a3456b-c789-1234-1de2-3cfa83096e25/-/crop/1:1/50p,50p/');

    // -/crop/:ratio/
    $url = (string) $transformation->cropByRatio(ratio: '1:1');
    expect($url)->toBe('https://ucarecdn.com/12a3456b-c789-1234-1de2-3cfa83096e25/-/crop/1:1/');
});

it('can resize', function () {
    $uuid = '12a3456b-c789-1234-1de2-3cfa83096e25';
    $transformation = uploadcare($uuid);

    // -/resize/:one_or_two_dimensions/
    $url = (string) $transformation->resize(width: 200, height: 200);
    expect($url)->toBe('https://ucarecdn.com/12a3456b-c789-1234-1de2-3cfa83096e25/-/resize/200x200/');

    // -/resize/:one_or_two_dimensions/
    $url = (string) $transformation->resize(width: null, height: 200);
    expect($url)->toBe('https://ucarecdn.com/12a3456b-c789-1234-1de2-3cfa83096e25/-/resize/200x/');

    // -/resize/:one_or_two_dimensions/
    $url = (string) $transformation->resize(width: 200, height: null);
    expect($url)->toBe('https://ucarecdn.com/12a3456b-c789-1234-1de2-3cfa83096e25/-/resize/200x/');

    // -/resize/:one_or_two_dimensions/-/stretch/:mode/ (optional)
    $url = (string) $transformation->resize(width: 200, height: 200, stretch: true, mode: 'on');
    expect($url)->toBe('https://ucarecdn.com/12a3456b-c789-1234-1de2-3cfa83096e25/-/stretch/on/-/resize/200x200/');
});

it('can scale crop', function () {
    $uuid = '12a3456b-c789-1234-1de2-3cfa83096e25';
    $transformation = uploadcare($uuid);

    // -/scale_crop/:dimensions/
    $url = (string) $transformation->scaleCrop(width: 200, height: 200);
    expect($url)->toBe('https://ucarecdn.com/12a3456b-c789-1234-1de2-3cfa83096e25/-/scale_crop/200x200/');

    // -/scale_crop/:dimensions/:alignment/
    $url = (string) $transformation->scaleCrop(width: 200, height: 200, offsetX: 'center');
    expect($url)->toBe('https://ucarecdn.com/12a3456b-c789-1234-1de2-3cfa83096e25/-/scale_crop/200x200/center/');

    // -/scale_crop/:dimensions/:alignment/
    $url = (string) $transformation->scaleCrop(width: 200, height: 200, offsetX: '50p', offsetY: '50p');
    expect($url)->toBe('https://ucarecdn.com/12a3456b-c789-1234-1de2-3cfa83096e25/-/scale_crop/200x200/50p,50p/');
});

it('can smart crop', function () {
    $uuid = '12a3456b-c789-1234-1de2-3cfa83096e25';
    $transformation = uploadcare($uuid);

    // -/scale_crop/:dimensions/:type
    $url = (string) $transformation->smartCrop(width: 200, height: 200, type: 'smart');
    expect($url)->toBe('https://ucarecdn.com/12a3456b-c789-1234-1de2-3cfa83096e25/-/scale_crop/200x200/smart/');

    // -/scale_crop/:dimensions/:type/:alignment
    $url = (string) $transformation->smartCrop(width: 200, height: 200, type: 'smart', offsetX: 'center');
    expect($url)->toBe('https://ucarecdn.com/12a3456b-c789-1234-1de2-3cfa83096e25/-/scale_crop/200x200/smart/center/');

    // -/scale_crop/:dimensions/:type/:alignment
    $url = (string) $transformation->smartCrop(width: 200, height: 200, type: 'smart', offsetX: '50p', offsetY: '50p');
    expect($url)->toBe('https://ucarecdn.com/12a3456b-c789-1234-1de2-3cfa83096e25/-/scale_crop/200x200/smart/50p,50p/');
});

it('can add an overlay', function () {
    $uuid = '12a3456b-c789-1234-1de2-3cfa83096e25';
    $uuidOverlay = 'e6b0c1c0-1b1a-4b1a-9b1a-1b1a1b1a1b1a';

    $transformation = uploadcare($uuid);

    // -/overlay/:uuid/
    $url = (string) $transformation->overlay($uuidOverlay);
    expect($url)->toBe('https://ucarecdn.com/12a3456b-c789-1234-1de2-3cfa83096e25/-/overlay/e6b0c1c0-1b1a-4b1a-9b1a-1b1a1b1a1b1a/');

    // -/overlay/:uuid/:relative_dimensions/:coordinateX/:coordinateY/:opacity/
    $url = (string) $transformation->overlay($uuidOverlay, width: 100, height: 100, coordinateX: 500, coordinateY: 300, opacity: '50p');
    expect($url)->toBe('https://ucarecdn.com/12a3456b-c789-1234-1de2-3cfa83096e25/-/overlay/e6b0c1c0-1b1a-4b1a-9b1a-1b1a1b1a1b1a/100x100/500,300/50p/');

    // -/overlay/:uuid/:relative_dimensions/:coordinateX/:coordinateY/
    $url = (string) $transformation->overlay($uuidOverlay, width: 100, height: 100, coordinateX: 500, coordinateY: 300);
    expect($url)->toBe('https://ucarecdn.com/12a3456b-c789-1234-1de2-3cfa83096e25/-/overlay/e6b0c1c0-1b1a-4b1a-9b1a-1b1a1b1a1b1a/100x100/500,300/');

    // -/overlay/:uuid/:relative_dimensions/:coordinateX/:coordinateY/
    $url = (string) $transformation->overlay($uuidOverlay, width: 100, height: 100, coordinateX: '50p', coordinateY: '30p');
    expect($url)->toBe('https://ucarecdn.com/12a3456b-c789-1234-1de2-3cfa83096e25/-/overlay/e6b0c1c0-1b1a-4b1a-9b1a-1b1a1b1a1b1a/100x100/50p,30p/');

    // -/overlay/:uuid/:relative_dimensions/:coordinateX/
    $url = (string) $transformation->overlay($uuidOverlay, width: 100, height: 100, coordinateX: 'center');
    expect($url)->toBe('https://ucarecdn.com/12a3456b-c789-1234-1de2-3cfa83096e25/-/overlay/e6b0c1c0-1b1a-4b1a-9b1a-1b1a1b1a1b1a/100x100/center/');

    // -/overlay/:uuid/:relative_dimensions/
    $url = (string) $transformation->overlay($uuidOverlay, width: 100, height: 100);
    expect($url)->toBe('https://ucarecdn.com/12a3456b-c789-1234-1de2-3cfa83096e25/-/overlay/e6b0c1c0-1b1a-4b1a-9b1a-1b1a1b1a1b1a/100x100/');

    // Test if skipping one of the parameters results in missing the remaining parameters
    $url = (string) $transformation->overlay($uuidOverlay, width: 100, height: 100, coordinateX: 500, coordinateY: null, opacity: '50p');
    expect($url)->toBe('https://ucarecdn.com/12a3456b-c789-1234-1de2-3cfa83096e25/-/overlay/e6b0c1c0-1b1a-4b1a-9b1a-1b1a1b1a1b1a/100x100/500/');
});


it('can strip meta information', function () {
    $uuid = '12a3456b-c789-1234-1de2-3cfa83096e25';
    $transformation = uploadcare($uuid);

    // -/strip_meta/all/ (default)
    $url = (string) $transformation->stripMeta();
    expect($url)->toBe('https://ucarecdn.com/12a3456b-c789-1234-1de2-3cfa83096e25/-/strip_meta/all/');

    // -/strip_meta/all/
    $url = (string) $transformation->stripMeta('all');
    expect($url)->toBe('https://ucarecdn.com/12a3456b-c789-1234-1de2-3cfa83096e25/-/strip_meta/all/');

    // -/strip_meta/none/
    $url = (string) $transformation->stripMeta('none');
    expect($url)->toBe('https://ucarecdn.com/12a3456b-c789-1234-1de2-3cfa83096e25/-/strip_meta/none/');

    // -/strip_meta/sensitive/
    $url = (string) $transformation->stripMeta('sensitive');
    expect($url)->toBe('https://ucarecdn.com/12a3456b-c789-1234-1de2-3cfa83096e25/-/strip_meta/sensitive/');
});

it('honors the boolean argument in autoRotate', function () {
    $uuid = '12a3456b-c789-1234-1de2-3cfa83096e25';

    expect((string) uploadcare($uuid)->autoRotate(true))->toContain('-/autorotate/yes/');
    expect((string) uploadcare($uuid)->autoRotate(false))->toContain('-/autorotate/no/');
});

it('uses the correct amount argument in blur', function () {
    expect((string) uploadcare('12a3456b-c789-1234-1de2-3cfa83096e25')->blur(10, 5))->toContain('-/blur/10/5/');
});
