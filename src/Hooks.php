<?php namespace Dredd;

/**
 * Class Hooks
 * @package Dredd
 */
class Hooks
{

    /**
     * @var array
     */
    public static $beforeHooks = [];

    /**
     * @var array
     */
    public static $beforeValidationHooks = [];

    /**
     * @var array
     */
    public static $afterHooks = [];


    /**
     * @var array
     */
    public static $beforeEachHooks = [];

    /**
     * @var array
     */
    public static $beforeEachValidationHooks = [];

    /**
     * @var array
     */
    public static $afterEachHooks = [];


    /**
     * @var array
     */
    public static $beforeAllHooks = [];

    /**
     * @var array
     */
    public static $afterAllHooks = [];

    /**
     * @param $transactionName
     * @param callable $function
     */
    public static function before($transactionName, callable $function)
    {
        $callback = new Callback($function, $transactionName);
        $transactionName = $callback->getName();

        if (! array_key_exists($transactionName, self::$beforeHooks)) {
            self::$beforeHooks[$transactionName] = [];
        }

        self::$beforeHooks[$transactionName][] = $callback;
    }

    /**
     * @param $transactionName
     * @param callable $function
     */
    public static function beforeValidation($transactionName, callable $function)
    {
        $callback = new Callback($function, $transactionName);
        $transactionName = $callback->getName();

        if (! array_key_exists($transactionName, self::$beforeValidationHooks)) {
            self::$beforeValidationHooks[$transactionName] = [];
        }

        self::$beforeValidationHooks[$transactionName][] = $callback;
    }

    /**
     * @param $transactionName
     * @param callable $function
     */
    public static function after($transactionName, callable $function)
    {
        $callback = new Callback($function, $transactionName);
        $transactionName = $callback->getName();

        if (! array_key_exists($transactionName, self::$afterHooks)) {
            self::$afterHooks[$transactionName] = [];
        }

        self::$afterHooks[$transactionName][] = $callback;
    }

    /**
     * @param callable $function
     */
    public static function beforeEach(callable $function)
    {
        $callback = new Callback($function);

        self::$beforeEachHooks[] = $callback;
    }

    /**
     * @param callable $function
     */
    public static function beforeEachValidation(callable $function)
    {
        $callback = new Callback($function);

        self::$beforeEachValidationHooks[] = $callback;
    }

    /**
     * @param callable $function
     */
    public static function afterEach(callable $function)
    {
        $callback = new Callback($function);

        self::$afterEachHooks[] = $callback;
    }

    /**
     * @param callable $function
     */
    public static function beforeAll(callable $function)
    {
        $callback = new Callback($function);

        self::$beforeAllHooks[] = $callback;
    }

    /**
     * @param callable $function
     */
    public static function afterAll(callable $function)
    {
        $callback = new Callback($function);

        self::$afterAllHooks[] = $callback;
    }

    /**
     * @param $propertyName
     * @param $transaction
     * @return array
     */
    public static function getCallbacksForName($propertyName, $transaction)
    {
        $callbacks = [];

        if (strpos($propertyName, 'All') || strpos($propertyName, 'Each')) {
            return Hooks::${$propertyName};
        }

        $name = $transaction->name;
        $tokens = explode(">", str_replace(" ", "", $name));
        $previous = '';

        foreach ($tokens as $token) {
            $previous .= $token . ">";

            if (array_key_exists($previous, Hooks::${$propertyName})) {
                $hooks = Hooks::${$propertyName}[$previous];

                foreach ($hooks as $hook) {
                    $callbacks[] = $hook;
                }
            }
        }

        if (array_key_exists($name, Hooks::${$propertyName})) {
            $hooks = Hooks::${$propertyName}[$name];

            foreach ($hooks as $hook) {
                $callbacks[] = $hook;
            }
        }

        return $callbacks;
    }

    /**
     * @param $arguments
     */
    public static function loadHooks($arguments)
    {
        // iterate through the arguments passed from stdin
        array_walk($arguments, function ($argument) {

            // iterate through the files passed back from expanding globs
            $files = glob($argument);
            array_walk($files, function ($file) {

                // require all files except dredd-hooks-php
                if (basename($file) != 'dredd-hooks-php') {
                    require_once $file;
                }
            });
        });
    }
}
