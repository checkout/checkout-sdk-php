<?php

namespace Checkout\Issuing\ControlProfiles\Requests;

use Checkout\Common\AbstractQueryFilter;

class ControlProfileQuery extends AbstractQueryFilter
{
    /**
     * The card's unique identifier.
     *
     * @var string
     */
    public $target_id;
}
