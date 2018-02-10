<?php

use Dredd\Hooks;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Artisan;

require __DIR__ . '/../../../vendor/autoload.php';

$app = require __DIR__ . '/../../../bootstrap/app.php';

$app->make(Kernel::class)->bootstrap();

Hooks::beforeAll(function (&$transaction) use ($app) {
    Artisan::call('migrate', ['--force' => true]);
});

Hooks::before('/users > GET', function(&$transaction) {
    factory(\App\User::class)->create([
            'name' => 'Dom',
            'email' => 'ddelnano@gmail.com',
        ]
    );
});

Hooks::afterAll(function (&$transaction) use ($app) {
    Artisan::call('migrate:rollback', ['--force' => true]);
});
