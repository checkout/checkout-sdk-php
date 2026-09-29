<?php

namespace Checkout\Payments\Setups\Common\Industry;

/**
 * A room booked by the customer, as declared on PaymentSetupAccommodation.room.
 *
 * Deliberately separate from Checkout\Payments\AccommodationRoom. The two schemas share a name
 * but not a shape: the payments one is {rate: string, number_of_nights_at_room_rate: string},
 * while this one is {rate: number, number_of_nights: integer, type: string}. Reusing the payments
 * class here sent the nights value under a key POST /payments/setups does not define, and made
 * the room type impossible to send at all.
 */
class PaymentSetupAccommodationRoom
{
    /**
     * The rate or cost of the room per day.
     * [Optional]
     *
     * @var float
     */
    public $rate;

    /**
     * The number of nights the room is booked for.
     * [Optional]
     *
     * @var int
     */
    public $number_of_nights;

    /**
     * The room class or type booked. For example, deluxe.
     * [Optional]
     *
     * @var string
     */
    public $type;
}
