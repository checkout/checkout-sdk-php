<?php

namespace Checkout\Issuing\ControlGroups\Requests;

use Checkout\Common\AbstractQueryFilter;

class ControlGroupQuery extends AbstractQueryFilter
{
    /**
     * The ID of the card or control profile. (Required)
     *
     * @var string
     */
    public $target_id;
}
