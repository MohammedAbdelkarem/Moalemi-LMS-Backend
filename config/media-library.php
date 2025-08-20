<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Media Library
    |--------------------------------------------------------------------------
    |
    | This is the configuration for the Spatie Media Library package.
    |
    */

    'disk_name' => env('MEDIA_DISK', 'media'),

    'max_file_size' => 1024 * 1024 * 100, // 100MB

    'queue_name' => env('MEDIA_QUEUE_NAME', 'media'),

    'path_generator' => Spatie\MediaLibrary\Support\PathGenerator\DefaultPathGenerator::class,

    'url_generator' => Spatie\MediaLibrary\Support\UrlGenerator\DefaultUrlGenerator::class,

    'moves_media_on_update' => false,

    'version_urls' => false,

    'image_optimizers' => [
        Spatie\ImageOptimizer\Optimizers\Jpegoptim::class => [
            '--strip-all',
            '--all-progressive',
        ],
        Spatie\ImageOptimizer\Optimizers\Pngquant::class => [
            '--force',
        ],
        Spatie\ImageOptimizer\Optimizers\Optipng::class => [
            '-i0',
            '-o2',
            '-quiet',
        ],
        Spatie\ImageOptimizer\Optimizers\Svgo::class => [
            '--disable=cleanupIDs',
        ],
        Spatie\ImageOptimizer\Optimizers\Gifsicle::class => [
            '-b',
            '-O3',
        ],
        Spatie\ImageOptimizer\Optimizers\Cwebp::class => [
            '-m 6',
            '-pass 10',
            '-mt',
            '-q 80',
        ],
    ],

    'media_model' => Spatie\MediaLibrary\MediaCollections\Models\Media::class,

    'remote' => [
        'extra_headers' => [
            'Cache-Control' => 'public, max-age=31536000',
        ],
    ],

    'ffmpeg' => [
        'ffmpeg_binary' => env('FFMPEG_PATH', '/usr/bin/ffmpeg'),
        'ffprobe_binary' => env('FFPROBE_PATH', '/usr/bin/ffprobe'),
        'timeout' => 3600,
        'threads' => 12,
    ],

    'path_generator_factory' => Spatie\MediaLibrary\Support\PathGenerator\PathGeneratorFactory::class,

    'url_generator_factory' => Spatie\MediaLibrary\Support\UrlGenerator\UrlGeneratorFactory::class,

];
