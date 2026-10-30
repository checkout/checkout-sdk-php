<?php

namespace Checkout\Payments\Request\Source;

/**
 * An entity source, with type "entity". Bank payouts only.
 */
class PayoutRequestEntitySource extends PayoutRequestSource
{
    public function __construct()
    {
        parent::__construct(PayoutSourceType::$entity);
    }

    /**
     * The entity or sub-entity's identifier, only applies to Platform Clients.
     * [Required]
     * ^(ent)_(\w{26})$
     * @var string
     */
    public $id;
}
