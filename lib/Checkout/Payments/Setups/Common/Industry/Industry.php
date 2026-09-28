<?php

namespace Checkout\Payments\Setups\Common\Industry;

/**
 * Industry-specific information for a payment setup.
 */
class Industry
{
    /**
     * Industry-specific information for airline bookings.
     * [Optional]
     *
     * Maps the specification property "airline", which is an array. This was previously named
     * $airline_data and held a single object, so the SDK sent an object under the key
     * "airline_data". Verified against the sandbox on 2026-09-28: a setup created that way comes
     * back from GET /payments/setups/{id} with the industry block absent entirely, so the whole
     * value was silently discarded. The array form under "airline" round-trips intact.
     *
     * @var AirlineData[]
     */
    public $airline;

    /**
     * Industry-specific information for accommodation bookings.
     * [Optional]
     *
     * Maps the specification property "accommodation", which is an array. Previously named
     * $accommodation_data and held a single object; see the note on $airline for the round-trip
     * evidence that the old shape was discarded.
     *
     * @var AccommodationData[]
     */
    public $accommodation;
}
