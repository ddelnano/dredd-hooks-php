<?php

use Dredd\Hooks;

class DreddHooksTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Hooks::$beforeAllHooks = [];
        Hooks::$afterAllHooks = [];

        Hooks::$beforeHooks = [];
        Hooks::$beforeValidationHooks = [];
        Hooks::$afterHooks = [];

        Hooks::$beforeEachHooks = [];
        Hooks::$beforeEachValidationHooks = [];
        Hooks::$afterEachHooks = [];
    }

    /**
     * @test
     */
    public function it_can_load_hooks()
    {
        Hooks::loadHooks([__DIR__ . "/../hooks/hookfile.php"]);

        $this->assertCount(1, Hooks::$beforeHooks);
    }

    /**
     * @test
     */

    public function it_can_load_multiple_before_hooks_for_same_transaction_name()
    {
        $name = 'transaction';

        Hooks::before($name, function(&$transaction) {

        });

        Hooks::before($name, function(&$transaction) {

        });

        $this->assertCount(2, Hooks::$beforeHooks[$name]);
    }
}