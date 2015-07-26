<?php

use App\User;

$factory(User::class, [
    'name' => $faker->name,
    'email' => $faker->unique()->email,
    'password' => $faker->word
]);
