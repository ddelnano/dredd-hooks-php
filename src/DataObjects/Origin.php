<?php

namespace Dredd\DataObjects;

/**
 * Reference to the transaction definition in the original API description document.
 * (See also {@link https://github.com/apiaryio/dredd-transactions#user-content-data-structures Dredd Transactions})
 */
class Origin
{
    /** @var string $filename */
    public $filename;

    /** @var string $apiName */
    public $apiName;

    /** @var string $resourceGroupName */
    public $resourceGroupName;

    /** @var string $resourceName */
    public $resourceName;

    /** @var string $actionName */
    public $actionName;

    /** @var string $exampleName */
    public $exampleName;

    public function __construct($origin)
    {
        $this->filename = $origin->filename;
        $this->apiName = $origin->apiName;
        $this->resourceGroupName = $origin->resourceGroupName;
        $this->resourceName = $origin->resourceName;
        $this->actionName = $origin->actionName;
        $this->exampleName = $origin->exampleName;
    }
}
