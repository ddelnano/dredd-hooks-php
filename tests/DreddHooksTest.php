<?php


use Dredd\Hooks;

class DreddHooksTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_can_load_hooks()
    {
        Hooks::loadHooks([__DIR__ . "/../hooks/hookfile.php"]);

        $this->assertCount(1, Hooks::$beforeHooks);
    }
}