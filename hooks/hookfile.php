<?php

use Dredd\Hooks;

Hooks::before('/message > GET', function(&$transaction)  {

    echo "before hook handled";
});

Hooks::after('/message > GET', function(&$transaction)  {

    echo "after hook handled";
});

Hooks::beforeValidation('/message > GET', function(&$transaction)  {

    echo 'before validation hook handled';
});

Hooks::beforeAll(function(&$transaction)  {

    echo 'before all hook handled';
});

Hooks::afterAll(function(&$transaction)  {

    echo 'after all hook handled';
});

Hooks::beforeEach(function(&$transaction)  {

    echo 'before each hook handled';
});

Hooks::beforeEachValidation(function(&$transaction)  {

    echo 'before each validation hook handled';
});

Hooks::afterEach(function(&$transaction)  {

    echo 'after each hook handled';
});