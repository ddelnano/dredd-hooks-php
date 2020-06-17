<?php

use Dredd\DataObjects\Transaction;
use Dredd\Hooks;

Hooks::beforeEach(function(Transaction &$transaction) {

    echo 'Transaction object';
});
