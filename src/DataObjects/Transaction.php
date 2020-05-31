<?php

namespace Dredd\DataObjects;

/**
 * Transaction object is passed as a first argument to
 *  {@link https://dredd.org/en/latest/hooks/index.html#hooks hook functions}
 *  and is one of the main public interfaces in Dredd.
 *
 * @link https://dredd.org/en/latest/data-structures.html#transaction-object
 */
class Transaction
{
    /** @var string $id */
    public $id;

    /**
     * Reference to the transaction definition in the original API description document
     * (See also {@link https://github.com/apiaryio/dredd-transactions#user-content-data-structures Dredd Transactions})
     *
     * @var string $name
     */
    public $name;

    /** @var string $host */
    public $host;

    /** @var int $port */
    public $port;

    /** @var string $protocol */
    public $protocol;

    /**
     * Expanded URI Template with parameters (if any) used for the HTTP request Dredd performs to the tested server
     *
     * @link https://tools.ietf.org/html/rfc6570.html
     * @var string $fullPath
     */
    public $fullPath;

    /**
     * Can be set to `true` and the transaction will be skipped
     *
     * @var bool $skip
     */
    public $skip;

    /**
     * Can be set to `true` or string and the transaction will fail
     *  - (string) - failure message with details why the transaction failed
     *  - (boolean)
     * @var bool|string $fail
     */
    public $fail;

    /** @var Origin $origin */
    public $origin;

    /**
     * Test data passed to Dredd’s reporters
     *
     * @link https://dredd.org/en/latest/data-structures.html#transaction-test
     * @var object
     */
    public $test;

    /**
     * Transaction runtime errors
     *
     * Whenever an exception occurs during a test run it’s being recorded under the errors property of the test.
     *
     * @link https://dredd.org/en/latest/data-structures.html#test-runtime-error
     * @var object
     */
    public $errors;

    /**
     * Transaction result equals to the result of the
     *  {@link https://github.com/apiaryio/gavel.js Gavel} validation library.
     *
     * @link https://dredd.org/en/latest/data-structures.html#transaction-results
     * @var object
     */
    public $results;

    /**
     * The HTTP request Dredd performs to the tested server, taken from the API description
     * @var Request $request
     */
    public $request;

    /** @var ExpectedResponse $expected */
    public $expected;

    /** @var RealResponse $real */
    public $real;

    public function __construct($transaction)
    {
        $this->id = $transaction->id;
        $this->name = $transaction->name;
        $this->host = $transaction->host;
        $this->port = $transaction->port;
        $this->protocol = $transaction->protocol;
        $this->fullPath = $transaction->fullPath;
        $this->skip = $transaction->skip;
        $this->fail = $transaction->fail;
        $this->origin = new Origin($transaction->origin);
        $this->request = new Request($transaction->request);
        $this->expected = new ExpectedResponse($transaction->expected);
        $this->real = new RealResponse($transaction->real);
    }
}
