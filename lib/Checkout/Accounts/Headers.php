<?php

namespace Checkout\Accounts;

class Headers
{
    /**
     * Value for the If-Match header, used to identify a specific version of a
     * reserve rule to update (for example "Y3Y9MCZydj0w"). Etag.
     *
     * @var string
     */
    public $if_match;

    /**
     * Value for the Accept header, used to negotiate the Accounts API schema version
     * (for example "application/json;schema_version=3.0").
     *
     * @var string
     */
    public $accept;
}
