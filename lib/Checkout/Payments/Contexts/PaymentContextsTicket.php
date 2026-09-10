<?php

namespace Checkout\Payments\Contexts;

use Checkout\Common\DateOnly;

class PaymentContextsTicket
{
    /**
     * @var string
     */
    public $number;

    /**
     * Format: yyyy-MM-dd
     * @var DateTime
     */
    #[DateOnly]
    public $issue_date;

    /**
     * @var string
     */
    public $issuing_carrier_code;

    /**
     * @var string
     */
    public $travel_package_indicator;

    /**
     * @var string
     */
    public $travel_agency_name;

    /**
     * @var string
     */
    public $travel_agency_code;
}
