<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use App\User;
use Illuminate\Http\Response;

Route::get('/', function () {
    return view('welcome');
});

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