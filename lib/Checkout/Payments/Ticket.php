<?php

namespace Checkout\Payments;

class Ticket
{
    /**
     * @var string
     */
    public $number;

    /**
     * @var string
     */
    public $issue_date;

    /**
     * @var string
     */
    public $issuing_carrier_code;

    /**
     * @var string
     */
    public $travel_agency_name;

    /**
     * @var string
     */
    public $travel_agency_code;
}
