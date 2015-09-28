<?php

use Dredd\Callback;

class CallbackTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function non_wildcard_callbacks_can_be_created()
    {
        $name = 'Admin';
        $func = function(&$transaction) {};
        $callback = new Callback($func, $name);

        $this->assertFalse($callback->isWildcard());
    }

    /**
     * @test
     */
    public function wildcard_callbacks_can_be_created()
    {
        $name = 'Admin > *';
        $func = function(&$transaction) {};
        $callback = new Callback($func, $name);

        $this->assertTrue($callback->isWildcard());
        $this->assertEquals('Admin>', $callback->getName());
    }

    /**
     * @test
     */
    public function deep_nested_wildcard_callbacks_work_as_expected()
    {
        $name = 'Deep > Nesting > Test > *';
        $func = function(&$transaction) {};
        $callback = new Callback($func, $name);

        $this->assertTrue($callback->isWildcard());
        $this->assertEquals('Deep>Nesting>Test>', $callback->getName());
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function it_prevents_multiple_wildcard_names()
    {
        $name = 'Wrong > * > Name > *';
        $func = function(&$transaction) {};
        $callback = new Callback($func, $name);
    }

    /**
     * @test
     */
    public function callback_do_not_need_a_name()
    {
        $callback = new Callback(function() {});

        $this->assertEquals("", $callback->getName());
    }
}