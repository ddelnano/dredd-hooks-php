<?php

namespace Dredd\DataObjects;

class Request
{
    /** @var string $body */
    public $body;

    /**
     * Can be manually set in {@link https://dredd.org/en/latest/hooks/index.html#hooks hooks}
     *  - `utf-8` (string) - indicates `body` contains a textual content encoded in UTF-8
     *  - `base64` (string) - indicates `body` contains a binary content encoded in Base64
     *
     * @var string $bodyEncoding
     * @psalm-var 'utf-8'|'base64' $bodyEncoding
     */
    public $bodyEncoding;

    /**
     * Keys are HTTP header names, values are HTTP header contents
     * @var array<string,string> $headers
     */
    public $headers;

    /**
     * Request URI as it was written in API description
     *
     * @var string $uri
     */
    public $uri;

    /** @var string $method */
    public $method;

    public function __construct($request)
    {
        $this->body = $request->body;
        $this->bodyEncoding = $request->bodyEncoding;
        $this->headers = (array) $request->headers;
        $this->uri = $request->uri;
        $this->method = $request->method;
    }
}
