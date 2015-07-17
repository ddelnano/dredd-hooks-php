<?php namespace Dredd;

class Hooks {

    public static $beforeHooks = [];

    public static $beforeValidationHooks = [];

    public static $afterHooks = [];


    public static $beforeEachHooks = [];

    public static $beforeEachValidationHooks = [];

    public static $afterEachHooks = [];


    public static $beforeAllHooks = [];

    public static $afterAllHooks = [];

    public static function before($transactionName, callable $function)
    {
        self::$beforeHooks[$transactionName] = [];

        self::$beforeHooks[$transactionName][] = $function;
    }

    public static function beforeValidation($transactionName, callable $function)
    {
        self::$beforeValidationHooks[$transactionName] = [];

        self::$beforeValidationHooks[$transactionName][] = $function;
    }

    public static function after($transactionName, callable $function)
    {
        self::$afterHooks[$transactionName] = [];

        self::$afterHooks[$transactionName][] = $function;
    }

    public static function beforeEach(callable $function)
    {
        self::$beforeEachHooks[] = $function;
    }

    public static function beforeEachValidation(callable $function)
    {
        self::$beforeEachValidationHooks[] = $function;
    }

    public static function afterEach(callable $function)
    {
        self::$afterEachHooks[] = $function;
    }

    public static function beforeAll(callable $function)
    {
        self::$beforeAllHooks[] = $function;
    }

    public static function afterAll(callable $function)
    {
        self::$afterAllHooks[] = $function;
    }

    public static function loadHooks($files)
    {
        $paths = $files;
        // iterate through the files passed from stdin
        array_walk($paths, function($file) {

            // iterate through the files passed back from expanding globs
            $globs = glob($file);
            array_walk($globs, function($path) {

                // require all files except dredd-hooks-php
                // TODO: the happy path should be the shortest
                if (basename($path) != 'dredd-hooks-php') {

                    require_once $path;
                }
            });
        });
    }
}