<?php

namespace Checkout\Forward\Requests;

class UpdateSecretRequest
{
    /**
     * Update the entity scope.
     *
     * @var string
     */
    public $entity_id;

    /**
     * New plaintext secret value. Max 8KB.
     *
     * @var string
     */
    public $value;
}
