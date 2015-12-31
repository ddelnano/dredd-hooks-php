<?php namespace Dredd;

use RuntimeException;

/**
 * Class Callback
 * @package Dredd
 */
class Callback
{

    /**
     * @var boolean
     */
    protected $wildcard;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var callable
     */
    private $callback;

    /**
     * Callback constructor.
     * @param callable $callback
     * @param string $name
     */
    public function __construct(callable $callback, $name = '')
    {
        $this->callback = $callback;
        $this->setName($name);
    }

    /**
     * @return bool
     */
    public function isWildcard()
    {
        return $this->wildcard;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     */
    public function setName($name)
    {
        $hasWildcard = strpos($name, "*") ? true : false;

        if ($hasWildcard) {

            $tokens = explode("*", $name);

            // There should not be more than 1 wildcard per name.
            if (count($tokens) > 2) throw new RuntimeException("Wildcard name should not contain more than 1 wildcard");

            $this->name = str_replace(" ", "", $tokens[0]);
            $this->wildcard = true;

            return;
        }

        $this->name = $name;
        $this->wildcard = false;
    }

    /**
     * @return callable
     */
    public function getCallback()
    {
        return $this->callback;
    }
}