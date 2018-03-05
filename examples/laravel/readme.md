The following is an example Laravel application that is tested using Dredd and dredd-hooks-php.

It shows how dredd-hooks-php can be utilized to seed a database for API endpoints being tested by Dredd.

## Assumptions

This example assumes you will be using Laravel homestead.   All of the files referenced in this section assume the root directory is `examples/laravel`

The `laravel.apib` file 

```
# My Api
## GET /user
+ Response 200 (application/json;charset=utf-8)
        {
            "user": {
                "name": "John Doe",
                "age": 22
            }
        }
## GET /users
+ Response 200 (application/json;charset=utf-8)
        {
            "users": [
                {
                    "name": "Dom",
                    "email": "ddelnano@gmail.com"
                }
            ]
        }
```

The laravel routes file (`app/Http/routes.php`)

```php
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
```

This file defines the two routes expected in the api blueprint file.  As you can see, the `/user` endpoint has the output hardcoded.  The `/users` endpoint however retrieves data from the database.  In order to seed the database before Dredd hits the endpoint, we can use a before hook.

The following hookfile uses Laravel's built in factory function to seed the database.

Here is the hookfile (`tests/dredd/hooks/hookfile.php`)
```php
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
```

## Running the example

1. Clone the repository
`git clone https://github.com/ddelnano/dredd-hooks-php.git`

2. Map the Laravel example into your homestead VM

```
# Homestead.yaml
folders:
  - map: ~/Code/dredd-hooks-php/examples/laravel
    to: /home/vagrant/code
```

3. Reload homestead
`vagrant reload --provision`

4. SSH to VM
`vagrant ssh`

2. Run make
`cd /home/vagrant/code`
`make`

Running make will do the following:
- composer install
- npm install
- run dredd

## Important Details

When writing tests for applications its very important to keep each test isolated from one another.  When testing web applications that use a database there are typically two ways to manage this: migrations or transactions.  When using database migrations in your test setup you would run your migrations and on teardown you would roll them back.  Transactions on the other hand you would start a transaction on setup and then rollback the transaction on teardown.  Using transactions is much faster, especially as the number of migrations in your application grows.  Unfortunately with dredd hooks there is no out of the box solution for using transactions.  Dredd-hooks-php and your web application are two separate php processes and since a transaction can only be seen on the same connection it will not work.  There are potential work arounds but it becomes complicated.  Please see [this issue](https://github.com/ddelnano/dredd-hooks-php/issues/34) for a more detailed explanation.
