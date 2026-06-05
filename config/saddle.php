<?php

declare(strict_types=1);

return [
    'path' => 'admin',

    'middleware' => ['web', 'auth'],

    'resources' => [
        'path' => app_path('Saddle'),
        'namespace' => 'App\\Saddle',
    ],

    'per_page' => 25,

    'brand' => [
        'name' => 'Saddle',
        'accent' => '#d9501f',
    ],

    /*
     * Opt-in multi-tenancy. Set 'model' to an Eloquent class to mount the
     * panel under /{path}/{tenant} and scope every data path to the resolved
     * tenant. 'relationship' is the tenant-side relation listing its members
     * (used for the membership check). null disables tenancy entirely, leaving
     * v0.5 behavior byte-identical. Changing this requires `php artisan
     * route:clear` because the {tenant} prefix is decided at boot.
     */
    'tenancy' => [
        'model' => null,
        'relationship' => 'users',
    ],
];
