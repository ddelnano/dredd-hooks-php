<?php

namespace Dredd\DataObjects;

class ExpectedResponse
{
    /** @var string $statusCode */
    public $statusCode;

    /**
     * Keys are HTTP header names, values are HTTP header contents
     *
     * @var array<string,string>
     */
    public $headers;

    /** @var string $body */
    public $body;

    /**
     * JSON Schema of the response body
     *
     * @var object $bodySchema
     */
    public $bodySchema;

    public function __construct($expected)
    {
        $this->statusCode = $expected->statusCode;
        $this->headers = (array) $expected->headers;
        $this->body = $expected->body;
        $this->bodySchema = $expected->bodySchema;
    }
}
