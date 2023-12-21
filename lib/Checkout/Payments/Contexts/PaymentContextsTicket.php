<?php

namespace Checkout\Payments\Contexts;

class PaymentContextsTicket
{
    /**
     * @var string
     */
    public $number;

    /**
     * @var DateTime
     */
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
