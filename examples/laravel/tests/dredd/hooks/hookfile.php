<?php

use Dredd\Hooks;

require __DIR__ . '/../../../vendor/autoload.php';

$app = require __DIR__ . '/../../../bootstrap/app.php';

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

Hooks::beforeAll(function (&$transaction) {
    Illuminate\Support\Facades\Artisan::call('migrate:refresh', ['--seed' => true]);
});

Hooks::beforeEach(function (&$transaction) use ($app) {
    $app->make('db')->beginTransaction();
});

Hooks::afterEach(function (&$transaction) use ($app) {
    $app->make('db')->rollback();
});

Hooks::before('/users > GET', function(&$transaction) {

    factory(\App\User::class)->create([
            'name' => 'Dom',
            'email' => 'ddelnano@gmail.com',
        ]
    );
});
