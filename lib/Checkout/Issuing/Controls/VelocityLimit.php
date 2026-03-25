<?php

namespace Checkout\Issuing\Controls;

class VelocityLimit
{
    /**
     * The amount the target can spend, in minor units. (Required)
     *
     * @var int
     */
    public $amount_limit;

    /**
     * The period of time over which the specified amount_limit can be spent. (Required)
     *
     * @var VelocityWindow
     */
    public $velocity_window;

    /**
     * The list of merchant category codes (MCCs) that the velocity limit applies to, as four-digit ISO 18245 codes.
     *
     * @var array of string
     */
    public $mcc_list;

    /**
     * The list of merchant identification (MID) codes to allow or block transactions from.
     *
     * @var array of string
     */
    public $mid_list;
}
