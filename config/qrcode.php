<?php

return [
    'disk' => env('QRCODE_STORAGE_DISK', env('FILESYSTEM_DRIVER', 'public')),

    'path' => env('QRCODE_STORAGE_PATH', 'qrcodes'),

    'visibility' => env('QRCODE_STORAGE_VISIBILITY', 'public'),

    'public_base_url' => env('QRCODE_PUBLIC_BASE_URL'),
];

