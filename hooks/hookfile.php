<?php

use Dredd\Hooks;

Hooks::before('/message > GET', function(&$transaction) use ($key) {

    echo "before hook handled";
});

Hooks::after('/message > GET', function(&$transaction) use ($key) {

    echo "after hook handled";
});

Hooks::beforeValidation('/message > GET', function(&$transaction) use ($key) {

    echo 'before validation hook handled';
});

Hooks::beforeAll(function(&$transaction) use ($key) {

    echo 'before all hook handled';
});

Hooks::afterAll(function(&$transaction) use ($key) {

    echo 'after all hook handled';
});

Hooks::beforeEach(function(&$transaction) use ($key) {

    echo 'before each hook handled';
});

Hooks::beforeEachValidation(function(&$transaction) use ($key) {

    echo 'before each validation hook handled';
});

Hooks::afterEach(function(&$transaction) use ($key) {

    echo 'after each hook handled';
});