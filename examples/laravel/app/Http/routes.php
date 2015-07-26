<?php

use App\User;
use Illuminate\Http\Response;

Route::get('/user', function () {

    $data = json_encode([
        'user' => [
            'name' => 'John Doe',
            'age' => 22
        ]
    ]);

    return (new Response($data, 200))->header('Content-Type', 'application/json;charset=utf-8');
});

Route::get('/users', function() {

    $users = User::all();

    return (new Response(['users' => $users], 200))->header('Content-Type', 'application/json;charset=utf-8');
});
