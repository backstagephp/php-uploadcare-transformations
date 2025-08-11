# Easy [Uploadcare](https://uploadcare.com/?via=vk10) Image Transformation URLs generation

[![Total Downloads](https://img.shields.io/packagist/dt/backstage/php-uploadcare-transformations.svg?style=flat-square)](https://packagist.org/packages/backstage/php-uploadcare-transformations)
[![Tests](https://github.com/backstage/php-uploadcare-transformations/actions/workflows/run-tests.yml/badge.svg?branch=main)](https://github.com/backstage/php-uploadcare-transformations/actions/workflows/run-tests.yml)
[![PHPStan](https://github.com/backstage/php-uploadcare-transformations/actions/workflows/phpstan.yml/badge.svg?branch=main)](https://github.com/backstage/php-uploadcare-transformations/actions/workflows/phpstan.yml)
![GitHub release (latest by date)](https://img.shields.io/github/v/release/backstage/php-uploadcare-transformations)
![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/backstage/php-uploadcare-transformations)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/backstage/php-uploadcare-transformations.svg?style=flat-square)](https://packagist.org/packages/backstage/php-uploadcare-transformations)

Generate [Uploadcare](https://uploadcare.com/?via=vk10) image processing URLs to transform and process your images. No need to write or generate the URL yourself. Just pass the UUID of the file, optionally pass the custom CDN and chain the methods you want to apply and the package generates the URL for you.

-   [Requirements](#requirements)
-   [Installation](#installation)
-   [Usage](#usage)
-   [Documentation](#documentation)
    -   [Using percentages or pixels as parameter](#using-percentages-or-pixels-as-parameter)
    -   [List of possible transformations](#list-of-possible-transformations)
-   [Usage](#usage-1)
    -   [Auto rotate](#auto-rotate)
    -   [Basic color adjustments](#basic-color-adjustments)
    -   [Blur](#blur)
    -   [Blur faces](#blur-faces)
    -   [Blur region](#blur-region)
    -   [Convert to sRGB](#convert-to-srgb)
    -   [Crop](#crop)
    -   [Crop by objects](#crop-by-objects)
    -   [Crop by ratio](#crop-by-ratio)
    -   [Enhance](#enhance)
    -   [Filter](#filter)
    -   [Flip](#flip)
    -   [Format](#format)
    -   [Grayscale](#grayscale)
    -   [ICC profile size threshold](#icc-profile-size-threshold)
    -   [Invert](#invert)
    -   [Miror](#miror)
    -   [Overlay](#overlay)
    -   [Preview](#preview)
    -   [Progressive](#progressive)
    -   [Quality](#quality)
    -   [Rasterize](#rasterize)
    -   [Resize](#resize)
    -   [Rotate](#rotate)
    -   [Scale crop](#scale-crop)
    -   [Set fill](#set-fill)
    -   [Sharpen](#sharpen)
    -   [Smart crop](#smart-crop)
    -   [Smart resize](#smart-resize)
    -   [Zoom objects](#zoom-objects)
-   [Testing](#testing)
-   [Changelog](#changelog)
-   [Contributing](#contributing)
-   [Security Vulnerabilities](#security-vulnerabilities)
-   [Credits](#credits)
-   [License](#license)

## Requirements

<ul>
  <li>PHP 8.1+</li>
</ul>

## Installation

You can install the package via composer:

```bash
composer require backstage/php-uploadcare-transformations
```

## Usage

<ol>
    <li>
        Import UploadcareTransformation.
    </li>
     <li>
        Get the UUID of the file you want to show.
    </li>
     <li>
        Set your CDN url (optional).
    </li>
     <li>
        Create the transformation URL by chaining one or multiple methods to the UploadcareTransformation class. You can chain as much methods as you want.
    </li>
    <li>Use the output of the transformation as image source.</li>
</ol>

```php
use Backstage\UploadcareTransformations\UploadcareTransformation;

$uuid = '12a3456b-c789-1234-1de2-3cfa83096e25';
$cdnUrl = 'https://example.com/cdn/';

$transformation = (new UploadcareTransformation($uuid, $cdnUrl));

$url = $transformation->crop(width: 320, height: '50p', offsetX: 'center')->setFill(color: 'ffffff');

echo $url;
// https://example.com/cdn/12a3456b-c789-1234-1de2-3cfa83096e25/-/crop/320x50p/center/-/set_fill/ffffff
```

If you prefer to use a shorter version you can also use the helper method:

```php
$uuid = '12a3456b-c789-1234-1de2-3cfa83096e25';
$cdnUrl = 'https://example.com/cdn/';

$url = uc($uuid, $cdnUrl)
        ->crop(width: 320, height: '50p', offsetX: 'center')
        ->setFill(color: 'ffffff')
        ->getUrl();

echo $url;
// https://example.com/cdn/12a3456b-c789-1234-1de2-3cfa83096e25/-/crop/320x50p/center/-/set_fill/ffffff
```

### Reusing Objects for Multiple Transformations

When you want to reuse an object for multiple transformations (like creating different formats for a picture element), you can use the `clone()` method. This is especially useful when you want to create multiple versions of the same image with different formats or transformations.

```php
$image = uc('your-uuid-here')->resize(width: 1024);

// Create different versions of the same image
$webp = $image->clone()->format('webp');
$jpeg = $image->clone()->format('jpeg');
$png = $image->clone()->format('png')->quality('best');
```

You can also use the `uc_clone()` helper function:

```php
$image = uc('your-uuid-here')->resize(width: 1024);
$webp = uc_clone($image)->format('webp');
$jpeg = uc_clone($image)->format('jpeg');
```

This is particularly useful in Blade components for creating responsive images:

```blade
<picture>
    <source srcset="{{ $webp }}" type="image/webp">
    <source srcset="{{ $jpeg }}" type="image/jpeg">
    <img src="{{ $jpeg }}" alt="Image">
</picture>
```

### Using the Laravel framework

When you are using the Laravel framework it might be better to define the CDN in your `.env` file and get the value from the `.env` file in a config file. You can create a `config/uploadcare` config or add it to the `config/services` file.

Your `.env` file:

```dotenv
UPLOADCARE_CDN_URL=https://ucarecdn.com
UPLOADCARE_PROXY_URL=https://endpoint.ucr.io
```

Add to `config/services.php` file:

```php
'uploadcare' => [
    'cdn_url' => env('UPLOADCARE_CDN_URL', 'https://ucarecdn.com'),
    'proxy_url' => env('UPLOADCARE_PROXY_URL', 'https://endpoint.ucr.io'),
],
```

In your code:

```php
$url = uc($uuid, config('services.uploadcare.cdn_url'))->crop(width: 320, height: '50p', offsetX: 'center')->setFill(color: 'ffffff');
```

## Documentation

### Using percentages or pixels as parameter

In some of the methods you can pass parameters in various ways. For example in the [scaleCrop()](/src/Transformations/ScaleCrop.php) method you can pass the offset in the form of a percentage or pixels. To make it easier to recognize when a pixel or percentage is used you can pass the parameters as following.

```php
// Using percentages
$url = $transformation->scaleCrop(width: 320, height: 320, offsetX: '50p', offsetY: '60p');
// https://example.com/cdn/12a3456b-c789-1234-1de2-3cfa83096e25/scale_crop/320x320/50px60p/


// Using pixels
$url = $transformation->scaleCrop(width: 320, height: 320, offsetX: 50, offsetY: 60);
// https://example.com/cdn/12a3456b-c789-1234-1de2-3cfa83096e25/scale_crop/320x320/50x60/
```

> As stated in the Uploadcare Documentation, in URLs, % is an escape character and should be encoded with %25 escape sequence, e.g. /scale_crop/440x440/80%25,80%25/. For convenience, we can use the p shortcut for percent which doesn't require encoding.

### List of possible transformations

Each transformation follows the documentation on Uploadcare which you may find <a href="https://uploadcare.com/docs/">here</a>.
The current list of possible transformations and where to find the documentation:

| Transformation                                            |                                                 Uploadcare Documentation link                                                  |
| --------------------------------------------------------- | :----------------------------------------------------------------------------------------------------------------------------: |
| [Auto rotate](#auto-rotate)                               |     <a target="_blank" href="https://uploadcare.com/docs/transformations/image/rotate-flip/#operation-autorotate">Link</a>     |
| [Basic color adjustments](#basic-color-adjustments)       |      <a target="_blank" href="https://uploadcare.com/docs/transformations/image/colors/#image-colors-operations">Link</a>      |
| [Blur](#blur)                                             |       <a target="_blank" href="https://uploadcare.com/docs/transformations/image/blur-sharpen/#operation-blur">Link</a>        |
| [Blur faces](#blur-faces)                                 | <a target="_blank" href="https://uploadcare.com/docs/transformations/image/blur-sharpen/#operation-blur-region-faces">Link</a> |
| [Blur region](#blur-region)                               |    <a target="_blank" href="https://uploadcare.com/docs/transformations/image/blur-sharpen/#operation-blur-region">Link</a>    |
| [Convert to sRGB](#convert-to-srgb)                       |          <a target="_blank" href="https://uploadcare.com/docs/transformations/image/colors/#operation-srgb">Link</a>           |
| [Crop](#crop)                                             |        <a target="_blank" href="https://uploadcare.com/docs/transformations/image/resize-crop/#operation-crop">Link</a>        |
| [Crop by objects](#crop-by-objects)                       |     <a target="_blank" href="https://uploadcare.com/docs/transformations/image/resize-crop/#operation-crop-tags">Link</a>      |
| [Crop by ratio](#crop-by-ratio)                           | <a target="_blank" href="https://uploadcare.com/docs/transformations/image/resize-crop/#operation-crop-aspect-ratio">Link</a>  |
| [Enhance](#enhance)                                       |         <a target="_blank" href="https://uploadcare.com/docs/transformations/image/colors/#operation-enhance">Link</a>         |
| [Filter](#filter)                                         |         <a target="_blank" href="https://uploadcare.com/docs/transformations/image/colors/#operation-filter">Link</a>          |
| [Flip](#flip)                                             |          <a target="_blank" href="https://uploadcare.com/docs/transformations/image/colors/#operation-flip">Link</a>           |
| [Format](#format)                                         |       <a target="_blank" href="https://uploadcare.com/docs/transformations/image/compression/#operation-format">Link</a>       |
| [Grayscale](#grayscale)                                   |        <a target="_blank" href="https://uploadcare.com/docs/transformations/image/colors/#operation-grayscale">Link</a>        |
| [ICC profile size threshold](#icc-profile-size-threshold) |      <a target="_blank" href="https://uploadcare.com/docs/transformations/image/colors/#operation-max-icc-size">Link</a>       |
| [Invert](#invert)                                         |        <a target="_blank" href="https://uploadcare.com/docs/transformations/image/colors/#operation-inverting">Link</a>        |
| [Miror](#miror)                                           |         <a target="_blank" href="https://uploadcare.com/docs/transformations/image/colors/#operation-mirror">Link</a>          |
| [Overlay](#overlay)                                       |          <a target="_blank" href="https://uploadcare.com/docs/transformations/image/overlay/#overlay-image">Link</a>           |
| [Preview](#preview)                                       |      <a target="_blank" href="https://uploadcare.com/docs/transformations/image/resize-crop/#operation-preview">Link</a>       |
| [Progressive](#progressive)                               |    <a target="_blank" href="https://uploadcare.com/docs/transformations/image/compression/#operation-progressive">Link</a>     |
| [Quality](#quality)                                       |      <a target="_blank" href="https://uploadcare.com/docs/transformations/image/compression/#operation-quality">Link</a>       |
| [Rasterize](#rasterize)                                   |                   <a target="_blank" href="https://uploadcare.com/docs/transformations/image/#svg">Link</a>                    |
| [Resize](#resize)                                         |       <a target="_blank" href="https://uploadcare.com/docs/transformations/image/resize-crop/#operation-resize">Link</a>       |
| [Rotate](#rotate)                                         |         <a target="_blank" href="https://uploadcare.com/docs/transformations/image/colors/#operation-rotate">Link</a>          |
| [Scale crop](#scale-crop)                                 |     <a target="_blank" href="https://uploadcare.com/docs/transformations/image/resize-crop/#operation-scale-crop">Link</a>     |
| [Set fill](#set-fill)                                     |      <a target="_blank" href="https://uploadcare.com/docs/transformations/image/resize-crop/#operation-setfill">Link</a>       |
| [Sharpen](#sharpen)                                       |         <a target="_blank" href="https://uploadcare.com/docs/transformations/image/colors/#operation-sharpen">Link</a>         |
| [Smart crop](#smart-crop)                                 |     <a target="_blank" href="https://uploadcare.com/docs/transformations/image/resize-crop/#operation-smart-crop">Link</a>     |
| [Smart resize](#smart-resize)                             |    <a target="_blank" href="https://uploadcare.com/docs/transformations/image/resize-crop/#operation-smart-resize">Link</a>    |
| [Zoom objects](#zoom-objects)                             |    <a target="_blank" href="https://uploadcare.com/docs/transformations/image/resize-crop/#operation-zoom-objects">Link</a>    |

## Usage

### Adding filename

Original filenames can be accessed via Uploadcare's REST API. This can be done by making a request to receive a JSON response with file parameters including `original_filename`.

You can set an optional filename that users will see instead of the original name:

```php
$url = $transformation->autoRotate(false)->filename('my-filename.jpg');
// https://example.com/cdn/.../-/autorotate/no/my-filename.jpg
```

> Please note that the filename should comply with <a href="https://tools.ietf.org/html/rfc3986#section-3.3">file name conventions</a>. For more more information about how to use filenames please check the <a href="https://uploadcare.com/docs/delivery/cdn/#cdn-filename">Uploadcare API documentation</a>.

### Auto rotate

The default behavior goes with parsing EXIF tags of original images and rotating them according to the “Orientation” tag. Using `false` as parameter allows turning off the default behavior.

```php
$url = $transformation->autoRotate(false);
// https://example.com/cdn/.../-/autorotate/no/

$url = $transformation->autoRotate(true);
// https://example.com/cdn/.../-/autorotate/yes/
```

### Basic color adjustments

The value parameter controls the strength of any applied adjustment. Ranges of the value parameter differ between operations. There also is a zero point for each operation — the value producing outputs equal to original images.

Adjustment (color) must be one of the following values: `brightness`, `exposure`, `contrast`, `saturation`, `gamma`, `vibrance`, `warmth`.

For a complete overview of allowed values based upon the chosen adjustment, take a look at the [Uploadcare Documentation](https://uploadcare.com/docs/transformations/image/colors/#image-colors-operations).

```php
$url = $transformation->basicColorAdjustments(color: 'brightness', value: 50);
// https://example.com/cdn/.../-/adjust/brightness/50/
```

### Blur

Blurs images by the strength factor. The filtering mode is Gaussian Blur, where strength parameter sets the blur radius — effect intensity.

```php
$url = $transformation->blur(strength: 50, amount: null);
// https://example.com/cdn/.../-/blur/50/
```

### Blur faces

When faces is specified the regions are selected automatically by utilizing face detection.

```php
$url = $transformation->blurFaces(strength: 50);
// https://example.com/cdn/.../-/blur_faces/50/
```

### Blur region

Blurs the specified region of the image by the strength factor. The filtering mode is Gaussian Blur, where strength parameter sets the blur radius — effect intensity.

Dimensions and coordinates must be pixels or percentages.

```php
// Using pixels.
$url = $transformation->blurRegion(dimensionX: 250, dimensionY: 250, coordinateX: 50, coordinateY: 50, strength: 200);
// https://example.com/cdn/.../-/blur_region/250/250/50/50/200/

// Using percentages.
$url = $transformation->blurRegion(dimensionX: '50p', dimensionY: '50p', coordinateX: '80p', coordinateY: '20p', strength: 200);
// https://example.com/cdn/.../-/blur_region/50p/50p/80p,20p/200/
```

### Convert to sRGB

The operation sets how Uploadcare behaves depending on different color profiles of uploaded images. See the [Uploadcare Documentation](https://uploadcare.com/docs/transformations/image/colors/#operation-srgb) to learn more about the possible outcomes.

The profile parameter must be one of the following values: `fast`, `icc`, `keep_profile`.

```php
$url = $transformation->convertToSRGB(profile: 'icc');
// https://example.com/cdn/.../-/srgb/icc/
```

### Crop

Crops an image by using specified dimensions and alignment.

Dimensions parameters can be in pixels or percentages. To see how pixels or percentages can be used, see the [Using percentages or pixels as parameter](#using-percentages-or-pixels-as-parameter) paragraph.

Alignment can also be in pixels and percentages but also a shortcut can be used. The possible values are: `top`, `center`, `bottom`, `left`, `right`.

```php
// Using percentages and a shortcut.
$url = $transformation->crop(width: 100, height: '50p', offsetX: 'center');
// https://example.com/cdn/.../-/crop/100x50p/center/

// Using pixels only.
$url = $transformation->crop(width: 100, height: 100, offsetX: 50, offsetY: 50);
// https://example.com/cdn/.../-/crop/100x100/50,50/

// Using both pixels and percentages.
$url = $transformation->crop(width: 100, height: '50p', offsetX: '25p', offsetY: '25p');
// https://example.com/cdn/.../-/crop/100x50p/25p,25p/
```

### Crop by objects

Crops the image to the object specified by the tag parameter.

Tag can be one of `face` or `image`.

Ratio are two numbers greater than zero separated by :. Ratio is the quotient from the division of these numbers.

Dimensions and alignment must be set in percentages. In case of the alignment you can also use the shortcut. The possible values are: `top`, `center`, `bottom`, `left`, `right`. If alignment is not specified, `center` value is used.

> Dimensions should be relative when used with a tag. So you are required to set both width and height with pixels.

```php
// Using no ratio, percentages and pixels combined.
$url = $transformation->cropByObjects(tag: 'face', ratio: null, width: '50p', height: '50p');
// https://example.com/cdn/../-/crop/face/50px50p/

// Using ratio, percentages and a shortcut.
$url = $transformation->cropByObjects(tag: 'face', ratio: '4:3', width: '50p', height: '50p', offsetX: 'bottom');
// https://example.com/cdn/../-/crop/face/4:3/50px50p/bottom/
```

### Crop by ratio

Crops the image to the specified aspect ratio, cutting off the rest of the image.

Ratio are two numbers greater than zero separated by :. Ratio is the quotient from the division of these numbers.

Alignment can be set in pixels and percentages but also a shortcut can be used. The possible values are: `top`, `center`, `bottom`, `left`, `right`. If alignment is not specified, `center` value is used.

```php
// Using percentages and a shortcut.
$url = $transformation->cropByRatio(ratio: '4:3', offsetX: 'bottom');
// https://example.com/cdn/.../-/crop/4:3/bottom/

// Using percentage in combination with pixels.
$url = $transformation->cropByRatio(ratio: '4:3', offsetX: '50p', offsetY: 240);
// https://example.com/cdn/.../-/crop/4:3/50p,240/
```

### Enhance

Auto-enhances an image by performing the following operations: auto levels, auto contrast, and saturation sharpening.

Strength must be a number between 0 and 100. Default value is 50.

```php
$url = $transformation->enhance(strength: 50);
// https://example.com/cdn/.../-/enhance/50/
```

### Filter

Applies one of predefined photo filters by its name. The way your images look affects their engagement rates. You apply filters thus providing beautiful images consistent across content pieces you publish.

The name parameter should be one of the following: `adaris`, `briaril`, `calarel`, `carris`, `cynarel`, `cyren`, `elmet`, `elonni`, `enzana`, `erydark`, `fenralan`, `ferand`, `galen`, `gavin`, `gethriel`, `iorill`, `iothari`, `iselva`, `jadis`, `lavra`, `misiara`, `namala`, `nerion`, `nethari`, `pamaya`, `sarnar`, `sedis`, `sewen`, `sorahel`, `sorlen`, `tarian`, `thellassan`, `varriel`, `varven`, `vevera`, `virkas`, `yedis`, `yllara`, `zatvel`, `zevcen`.

The amount parameter must be a number between -100 and 200.

```php
$url = $transformation->filter(name: 'adaris', value: 50);
// https://example.com/cdn/.../-/filter/adaris/50/
```

### Flip

Flips images.

```php
$url = $transformation->flip();
// https://example.com/cdn/.../-/flip/
```

### Format

Converts an image to one of the following formats: `jpg`, `png`, `webp`, `auto`.

```php
$url = $transformation->format(format: 'jpeg');
// https://example.com/cdn/.../-/format/jpg/
```

### Grayscale

Desaturates images. The operation has no additional parameters and simply produces a grayscale image output when applied.

```php
$url = $transformation->grayscale();
// https://example.com/cdn/.../-/grayscale/
```

### ICC profile size threshold

The operation defines which RGB color profile sizes will be considered “small” and “large” when using srgb in `fast` or `icc` modes. The `number` stands for the ICC profile size in kilobytes.

The default value is 10 (10240 bytes). Most of the common RGB profile sizes (sRGB, Display P3, ProPhoto, Adobe RGB, Apple RGB) are below the threshold.

> Please note, that because this transformation should always be used in combination with [`convertToSRGB()`](#convert-to-srgb) its method should be called <strong>after</strong> `convertToSRGB()`. Otherwise the ICC profile size threshold gets overwritten by the `convertToSRGB()` transformation.

```php
$url = $transformation->convertToSRGB(profile: 'fast')->iccProfileSizeThreshold(number: 10);
// https://example.com/cdn/.../-/max_icc_size/10/srgb/fast/
```

### Invert

Inverts images rendering a 'negative' of the input.

```php
$url = $transformation->invert();
// https://example.com/cdn/.../-/invert/
```

### Miror

Mirrors images.

```php
$url = $transformation->mirror();
// https://example.com/cdn/.../-/mirror/
```

### Overlay

The overlay operation allows to layer images one over another. One of the most common use cases here are watermarks: semi-transparent images layered over opaque ones to complicate their unauthorized usage, etc.

> Every overlay parameter is optional and can be omitted. However, the order of parameter URL directives should be preserved. For example, if you want to use `coordinateX` parameter, you should also specify `width` and `height` parameters. Also note that the `opacity` parameter should be specified with percentages.

```php
$uuidOverlay = 'e6b0c1c0-1b1a-4b1a-9b1a-1b1a1b1a1b1a';

$url = $transformation->overlay(
  uuid: $uuidOverlay,
  width: 150,
  height: 150,
  coordinateX: 200,
  coordinateY: 300,
  opacity: '25p'
)->getUrl();
// https://example.com/cdn/.../-/overlay/e6b0c1c0-1b1a-4b1a-9b1a-1b1a1b1a1b1a/150x150/200,300/25p/
```

### Preview

Downscales an image proportionally to fit the given width and height in pixels.

```php
$url = $transformation->preview(width: 100, height: 100);
// https://example.com/cdn/.../-/preview/100x100/
```

### Progressive

Returns a progressive image. In progressive images, data are compressed in multiple passes of progressively higher detail. The operation does not affect non-JPEG images; does not force image formats to JPEG.

```php
$url = $transformation->progressive(true);
// https://example.com/cdn/.../-/progressive/yes/

$url = $transformation->progressive(false);
// https://example.com/cdn/.../-/progressive/no/
```

### Quality

Sets output JPEG and WebP quality. Since actual settings vary from codec to codec, and more importantly, from format to format, we provide five simple tiers and two automatic values which suits most cases of image distribution and are consistent.

Quality must be one of the following values: `smart`, `smart_retina`, `normal`, `better`, `best`, `lighter`, `lightest`.

```php
$url = $transformation->quality(quality: 'smart');
// https://example.com/cdn/.../-/quality/smart/
```

### Rasterize

Rasterize SVG images.

```php
$url = $transformation->rasterize();
// https://example.com/cdn/.../-/rasterize/
```

### Resize

Resizes an image to one or two dimensions. When you set both width and height explicitly, it may result in a distorted image. If you specify either side, this operation will preserve the original aspect ratio and resize the image accordingly. Mode should be one of the following values: `on`, `off`, `fill`.

```php
// Using width, height, stretch and 'fill' mode.
$url = $transformation->resize(width: 100, height: null, stretch: true, mode: 'fill');
// https://example.com/cdn/.../-/resize/100x/stretch/fill/

// Using only height, no stretch and no mode.
$url = $transformations->resize(width: null, height: 250, stretch: false);
// https://example.com/cdn/.../-/resize/250x/
```

### Rotate

Right-angle image rotation, counterclockwise. The value of angle must be a multiple of 90.

```php
$url = $transformation->rotate(angle: 180);
// https://example.com/cdn/.../-/rotate/180/
```

### Scale crop

Scales an image until it fully covers the specified dimensions; the rest gets cropped. Mostly used to place images with various dimensions into placeholders (e.g., square shaped).

Dimensions must be set in pixels.

Alignment must be set in percentages or shortcut. The possible values are: `top`, `center`, `bottom`, `left`, `right`. If alignment is not specified, `0,0` value is used.

```php
// Using percentages.
$url = $transformation->scaleCrop(width: 100, height: 100, offsetX: '30p', offsetY: '50p');
// https://example.com/cdn/.../-/scale_crop/100x100/30p,50p/

// Using shortcut.
$url = $transformation->scaleCrop(width: 100, height: 100, offsetX: 'bottom');
// https://example.com/cdn/../-/scale_crop/100x100/bottom/
```

### Set fill

Sets the fill color used with crop, stretch or when converting an alpha channel enabled image to JPEG.

Color must be a hex color code <b>without using the hashtag</b>.

```php
$url = $transformation->setFill(color: 'ff0000');
// https://example.com/cdn/.../-/setfill/ff0000/
```

### Sharpen

Sharpens an image, might be especially useful with images that were subjected to downscaling. strength can be in the range from 0 to 20 and defaults to the value of 5.

```php
$url = $transformation->sharpen(strength: 20);
// https://example.com/cdn/.../-/sharp/20/
```

### Smart crop

Switching the crop type to one of the smart modes enables the content-aware mechanics. Uploadcare applies AI-based algorithms to detect faces and other visually sensible objects to crop the background and not the main object.

Dimensions must be set in pixels.

Type must be one of the following values: `smart`, `smart_faces_objects`, `smart_faces`, `smart_objects`, `smart_faces_points`, `smart_points`, `smart_objects_faces_points`, `smart_objects_points` or `smart_objects_faces`.

Aligment must be set in percentages or shortcut. The possible values are: `top`, `center`, `bottom`, `left`, `right`. If alignment is not specified, `0,0` value is used.

```php
// Using percentages.
$url = $transformation->smartCrop(width: 100, height: 100, type: 'smart_faces_objects', offsetX: '30p', offsetY: '50p');
// https://example.com/cdn/.../-/scale_crop/100x100/smart_faces_objects/30p,50p/

// Using shortcut.
$url = $transformation->smartCrop(width: 100, height: 100, type: 'smart_faces_objects', offsetX: 'right');
// https://example.com/cdn/.../-/scale_crop/100x100/smart_faces_objects/right/
```

### Smart resize

Content-aware resize helps retaining original proportions of faces and other visually sensible objects while resizing the rest of the image using intelligent algorithms.

```php
$url = $transformation->smartResize(width: 500, height: 500);
// https://example.com/cdn/.../-/smart_resize/500x500/
```

### Zoom objects

Zoom objects operation is best suited for images with solid or uniform backgrounds.

Zoom must be a number between 1 and 100.

```php
$url = $transformation->zoomObjects(zoom: 50);
// https://example.com/cdn/.../-/zoom_objects/50/
```

## Testing

```bash
./-/vendor/bin/pest
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../-/../-/security/policy) on how to report security vulnerabilities.

## Credits

-   [Bas van Dinther](https://github.com/baspa)
-   [Mark van Eijk](https://github.com/markvaneijk)
-   [All Contributors](../-/../-/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
