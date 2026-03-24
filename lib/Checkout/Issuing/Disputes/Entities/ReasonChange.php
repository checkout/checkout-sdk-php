<?php

namespace Checkout\Issuing\Disputes\Entities;

class ReasonChange
{
    /**
     * The updated four-digit scheme-specific reason code for the chargeback. (Required)
     *
     * @var string
     */
    public $reason;

    /**
     * Your justification for changing the dispute reason. (Required)
     *
     * @var string
     */
    public $justification;
}
