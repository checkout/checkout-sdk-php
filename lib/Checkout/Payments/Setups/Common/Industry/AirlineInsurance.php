<?php

namespace Checkout\Payments\Setups\Common\Industry;

class AirlineInsurance
{
    /**
     * The type of travel insurance purchased with the booking.
     * [Optional]
     * @var string
     */
    public $type;

    /**
     * The name of the insurance company.
     * [Optional]
     * @var string
     */
    public $company;

    /**
     * The price of the travel insurance.
     * [Optional]
     * @var AirlineInsurancePrice
     */
    public $price;
}
