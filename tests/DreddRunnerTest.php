<?php


use Dredd\Hooks;

class DreddRunnerTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var \Dredd\Runner
     */
    protected $runner;

    public function setUp()
    {
        $this->runner = new Dredd\Runner;

        Hooks::$beforeValidationHooks = [];
        Hooks::$beforeHooks = [];
        Hooks::$afterHooks = [];

        Hooks::$beforeEachHooks = [];
        Hooks::$beforeEachValidationHooks= [];
        Hooks::$afterEachHooks= [];

        Hooks::$beforeAllHooks = [];
        Hooks::$afterAllHooks = [];
    }

    /**
     * @test
     */

    public function it_can_get_beforeHooks_from_DreddHooks()
    {
        $propName = $this->runner->getPropertyNameFromMethodCall('runBeforeHooksForTransaction');

        $this->assertEquals("beforeHooks", $propName);
    }

    /**
     * @test
     */

    public function it_can_get_afterHooks_from_DreddHooks()
    {
        $propName = $this->runner->getPropertyNameFromMethodCall('runAfterHooksForTransaction');

        $this->assertEquals("afterHooks", $propName);
    }

    /**
     * @test
     */

    public function it_can_get_beforeValidationHooks_from_DreddHooks()
    {
        $propName = $this->runner->getPropertyNameFromMethodCall('runBeforeValidationHooksForTransaction');

        $this->assertEquals("beforeValidationHooks", $propName);
    }

    /**
     * @test
     */

    public function it_can_get_beforeEachHooks_from_DreddHooks()
    {
        $propName = $this->runner->getPropertyNameFromMethodCall('runBeforeEachHooksForTransaction');

        $this->assertEquals("beforeEachHooks", $propName);
    }

    /**
     * @test
     */

    public function it_can_get_afterEachHooks_from_DreddHooks()
    {
        $propName = $this->runner->getPropertyNameFromMethodCall('runAfterEachHooksForTransaction');

        $this->assertEquals("afterEachHooks", $propName);
    }

    /**
     * @test
     */

    public function it_can_get_beforeEachValidationHooks_from_DreddHooks()
    {
        $propName = $this->runner->getPropertyNameFromMethodCall('runBeforeEachValidationHooksForTransaction');

        $this->assertEquals("beforeEachValidationHooks", $propName);
    }

    /**
     * @test
     */

    public function it_can_get_beforeAllHooks_from_DreddHooks()
    {
        $propName = $this->runner->getPropertyNameFromMethodCall('runBeforeAllHooksForTransaction');

        $this->assertEquals("beforeAllHooks", $propName);
    }

    /**
     * @test
     */

    public function it_can_get_afterAllHooks_from_DreddHooks()
    {
        $propName = $this->runner->getPropertyNameFromMethodCall('runAfterAllHooksForTransaction');

        $this->assertEquals("afterAllHooks", $propName);
    }

    /**
     * @test
     */

    public function it_can_run_beforeHooks()
    {
        $transactionName = 'transaction';

        $transaction = new stdClass();
        $transaction->name = $transactionName;

        Hooks::before($transactionName, function($transaction) {
            echo "The callback is being executed";
        });

        $this->runner->runBeforeHooksForTransaction($transaction);
        $this->expectOutputString("The callback is being executed");
    }

    /**
     * @test
     */

    public function it_can_run_beforeEachHooks()
    {
        $transactionName = 'transaction';

        $transaction = new stdClass();
        $transaction->name = $transactionName;

        Hooks::beforeEach(function($transaction) {
            echo "yay its called";
        });

        $this->runner->runBeforeEachHooksForTransaction($transaction);
        $this->expectOutputString("yay its called");
    }

    /**
     * @test
     */

    public function it_can_cause_transaction_to_fail()
    {
        $transactionName = 'transaction';

        $transaction = new stdClass();
        $transaction->name = $transactionName;
        $transaction->fail = false;

        Hooks::before($transactionName, function(&$transaction) {
            $transaction->fail = true;
        });

        $this->assertFalse($transaction->fail);

        $status = $this->runner->runBeforeHooksForTransaction($transaction);

        $this->assertEquals(true, $status->fail);
    }

    /**
     * @test
     */

    public function it_can_cause_before_each_transaction_to_fail()
    {
        $transactionName = 'transaction';

        $transaction = new stdClass();
        $transaction->name = $transactionName;
        $transaction->fail = false;

        Hooks::beforeEach(function(&$transaction) {
            $transaction->fail = true;
        });

        $this->assertFalse($transaction->fail);

        $status = $this->runner->runBeforeEachHooksForTransaction($transaction);

        $this->assertEquals(true, $status->fail);
    }

}