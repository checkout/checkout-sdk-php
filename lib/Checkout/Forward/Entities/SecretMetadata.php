<?php

namespace Checkout\Forward\Entities;

class SecretMetadata
{
    /**
     * Secret name (1-64 characters, alphanumeric and underscore)
     *
     * @var string
     */
    public $name;

    /**
     * Timestamp when the secret was created.
     *
     * @var string
     */
    public $created_at;

    /**
     * Timestamp when the secret was last updated.
     *
     * @var string
     */
    public $updated_at;

    /**
     * Version number.
     *
     * @var int
     */
    public $version;

    /**
     * Entity ID if the secret is scoped to a specific entity.
     *
     * @var string
     */
    public $entity_id;
}
