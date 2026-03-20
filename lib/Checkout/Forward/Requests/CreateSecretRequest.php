<?php

namespace Checkout\Forward\Requests;

class CreateSecretRequest
{
    /**
     * Secret name. 1-64 characters, alphanumeric and underscore. (Required)
     *
     * @var string
     */
    public $name;

    /**
     * Plaintext secret value. Max 8KB. (Required)
     *
     * @var string
     */
    public $value;

    /**
     * Optional. When provided, the secret is scoped to this entity.
     *
     * @var string
     */
    public $entity_id;
}
