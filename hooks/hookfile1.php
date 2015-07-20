<?php

use Dredd\Hooks;

Hooks::before("/message > GET", function($transaction) {

    fprintf(STDOUT, "It's me, File1");
    flush();
});