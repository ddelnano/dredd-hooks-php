<?php

use App\User;
use Dredd\Hooks;
use Illuminate\Database\Capsule\Manager;
use Laracasts\TestDummy\Factory;

$manager = new Manager();

$config = require __DIR__ . "/../../../config/database.php";

$manager->addConnection($config['connections']['sqlite']);
$manager->bootEloquent();
$manager->setAsGlobal();

$manager->getConnection()->beginTransaction();

$factory = new Factory(__DIR__ . "/../../../tests/factories");


Hooks::before('/users > GET', function(&$transaction) {

    Factory::create(User::class, [
        'name' => 'Dom',
        'email' => 'ddelnano@gmail.com'
    ]);
});

Hooks::after('/users > GET', function(&$transaction) use($manager) {

    $manager->getConnection()->rollback();
});