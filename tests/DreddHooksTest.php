<?php

use Dredd\Hooks;

/**
 * Class DreddHooksTest
 */
class DreddHooksTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    public $className = "Dredd\\Callback";

    /**
     *
     */
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

    /**
     * @test
     */
    public function it_stores_wildcard_hooks_at_appropriate_index()
    {
        Hooks::after('Admin > *', function(&$transaction) {

        });

        $this->assertCount(1, Hooks::$afterHooks['Admin>']);
    }

    /**
     * @test
     */
    public function it_can_get_hooks_without_names()
    {
        $transaction = new stdClass();
        $transaction->name = 'Admin > ';

        Hooks::beforeEach(function(&$transaction) {

        });

        $hooks = Hooks::getCallbacksForName('beforeEachHooks', $transaction);
        $this->assertCount(1, $hooks);
        $this->assertTrue(is_a($hooks[0], $this->className), sprintf("Expected %s received %s", $this->className, get_class($hooks[0])));
    }

    /**
     * @test
     */
    public function it_can_get_hooks_with_non_wildcard_names()
    {
        $transaction = new stdClass();
        $transaction->name = 'Test';

        Hooks::before('Test', function(&$transaction) {});

        $hooks = Hooks::getCallbacksForName('beforeHooks', $transaction);
        $this->assertCount(1, $hooks);
        $this->assertTrue(is_a($hooks[0], $this->className), sprintf("Expected %s received %s", $this->className, get_class($hooks[0])));
    }

    /**
     * @test
     */
    public function it_can_get_hooks_with_wildcards()
    {
        $transaction = new stdClass();
        $transaction->name = 'Admin > Test';

        Hooks::before('Admin > *', function(&$transaction) {});

        $hooks = Hooks::getCallbacksForName('beforeHooks', $transaction);
        $this->assertCount(1, $hooks);
        $this->assertTrue(is_a($hooks[0], $this->className), sprintf("Expected %s received %s", $this->className, get_class($hooks[0])));
    }
}