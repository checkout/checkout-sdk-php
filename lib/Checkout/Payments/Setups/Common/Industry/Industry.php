<?php

namespace Checkout\Payments\Setups\Common\Industry;

class Industry
{
    /**
     * Industry-specific information for airline bookings.
     * [Optional]
     * @var AirlineData
     */
    public $airline_data;

    /**
     * Industry-specific information for accommodation bookings.
     * [Optional]
     * @var AccommodationData
     */
    public $accommodation_data;
}
