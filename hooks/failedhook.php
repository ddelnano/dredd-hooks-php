<?php

use Dredd\Hooks;

Hooks::beforeEach(function(&$transaction) {

    $transaction->failed = true;
    echo 'Yay! Failed!';
});
