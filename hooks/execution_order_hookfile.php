<?php

use Dredd\Hooks;

$key = 'hook_modifications';

Hooks::before('/message > GET', function($transaction) use ($key) {

    $transaction->key = [];
    $transaction->$key[] = 'before modification';
});

Hooks::after('/message > GET', function($transaction) use ($key) {

    $transaction->key = [];
    $transaction->$key[] = 'after modification';
});

Hooks::beforeValidation('/message > GET', function($transaction) use ($key) {

    $transaction->key = [];
    $transaction->$key[] = 'before validation modification';
});

Hooks::beforeAll(function($transaction) use ($key) {

    $transaction->key = [];
    $transaction->$key[] = 'before all modification';
});

Hooks::afterAll(function($transaction) use ($key) {

    $transaction->key = [];
    $transaction->$key[] = 'after all modification';
});

Hooks::beforeEach(function($transaction) use ($key) {

    $transaction->key = [];
    $transaction->$key[] = 'before each modification';
});

Hooks::beforeEachValidation(function($transaction) use ($key) {

    $transaction->key = [];
    $transaction->$key[] = 'before each validation modification';
});

Hooks::afterEach(function($transaction) use ($key) {

    $transaction->key = [];
    $transaction->$key[] = 'after each modification';
});
