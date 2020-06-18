<?php

namespace Dredd\DataObjects;

class RealResponse
{
    /** @var string $statusCode */
    public $statusCode;

    /**
     * Keys are HTTP header names, values are HTTP header contents
     *
     * @var object
     */
    public $headers;

    /** @var string $body */
    public $body;

    /**
     *  - `utf-8` (string) - indicates `body` contains a textual content encoded in UTF-8
     *  - `base64` (string) - indicates `body` contains a binary content encoded in Base64
     *
     * @var string $bodyEncoding
     * @psalm-var 'utf-8'|'base64' $bodyEncoding
     */
    public $bodyEncoding;

    public function __construct($expected)
    {
        $this->statusCode = $expected->statusCode;
        $this->headers = $expected->headers;
        $this->body = $expected->body;
        $this->bodyEncoding = $expected->bodyEncoding;
    }
}
