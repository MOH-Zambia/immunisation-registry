<?php

return [
    'disk' => env('CERTIFICATE_STORAGE_DISK', env('FILESYSTEM_DRIVER', 'public')),

    'path' => env('CERTIFICATE_STORAGE_PATH', 'certificates'),

    'visibility' => env('CERTIFICATE_STORAGE_VISIBILITY', 'private'),
];
