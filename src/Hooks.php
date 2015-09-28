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
        $callback = new Callback($function, $transactionName);
        $transactionName = $callback->getName();

        if ( ! array_key_exists($transactionName, self::$beforeHooks)) self::$beforeHooks[$transactionName] = [];

        self::$beforeHooks[$transactionName][] = $callback;
    }

    public static function beforeValidation($transactionName, callable $function)
    {
        $callback = new Callback($function, $transactionName);
        $transactionName = $callback->getName();

        if ( ! array_key_exists($transactionName, self::$beforeValidationHooks)) self::$beforeValidationHooks[$transactionName] = [];

        self::$beforeValidationHooks[$transactionName][] = $callback;
    }

    public static function after($transactionName, callable $function)
    {
        $callback = new Callback($function, $transactionName);
        $transactionName = $callback->getName();

        if ( ! array_key_exists($transactionName, self::$afterHooks)) self::$afterHooks[$transactionName] = [];

        self::$afterHooks[$transactionName][] = $callback;
    }

    public static function beforeEach(callable $function)
    {
        $callback = new Callback($function);

        self::$beforeEachHooks[] = $callback;
    }

    public static function beforeEachValidation(callable $function)
    {
        $callback = new Callback($function);

        self::$beforeEachValidationHooks[] = $callback;
    }

    public static function afterEach(callable $function)
    {
        $callback = new Callback($function);

        self::$afterEachHooks[] = $callback;
    }

    public static function beforeAll(callable $function)
    {
        $callback = new Callback($function);

        self::$beforeAllHooks[] = $callback;
    }

    public static function afterAll(callable $function)
    {
        $callback = new Callback($function);

        self::$afterAllHooks[] = $callback;
    }

    public static function getCallbacksForName($propertyName, $transaction)
    {
        $callbacks = [];

        if (strpos($propertyName, 'All') || strpos($propertyName, 'Each')) {

            return Hooks::${$propertyName};
        }

        $name = $transaction->name;
        preg_match("/\\w+\\s*>/", str_replace(" ", "", $name), $tokens);

        $previous = '';

        foreach ($tokens as $token) {

            if (array_key_exists($token, Hooks::${$propertyName})) {

                $hooks = Hooks::${$propertyName}[$token];

                foreach ($hooks as $hook) {

                    $callbacks[] = $hook;
                }
            }

            $previous .= $token;
        }

        if (array_key_exists($name, Hooks::${$propertyName})) {

            $hooks = Hooks::${$propertyName}[$name];

            foreach ($hooks as $hook) {

                $callbacks[] = $hook;
            }
        }

        return $callbacks;
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
                if (basename($path) != 'dredd-hooks-php') {

                    require_once $path;
                }
            });
        });
    }
}
