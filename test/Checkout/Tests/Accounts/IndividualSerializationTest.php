<?php

namespace Checkout\Tests\Accounts;

use Checkout\Accounts\DateOfBirth;
use Checkout\Accounts\Identification;
use Checkout\Accounts\Individual;
use Checkout\Accounts\PlaceOfBirth;
use Checkout\Common\Address;
use Checkout\Common\Country;
use Checkout\JsonSerializer;
use PHPUnit\Framework\TestCase;

/**
 * The top-level individual of the Sole Trader (2.0) variants. place_of_birth is defined by the
 * EEA variants, identification by the US ones; the model carries both.
 */
class IndividualSerializationTest extends TestCase
{
    public function testV2IndividualRoundTrip()
    {
        $address = new Address();
        $address->address_line1 = "123 Main Street";
        $address->address_line2 = "Suite 100";
        $address->city = "San Francisco";
        $address->state = "CA";
        $address->zip = "94105";
        $address->country = Country::$US;

        $dateOfBirth = new DateOfBirth();
        $dateOfBirth->day = 15;
        $dateOfBirth->month = 1;
        $dateOfBirth->year = 1990;

        $placeOfBirth = new PlaceOfBirth();
        $placeOfBirth->country = Country::$US;

        $identification = new Identification();
        $identification->national_id_number = "123456789";

        $individual = new Individual();
        $individual->first_name = "John";
        $individual->middle_name = "Robert";
        $individual->last_name = "Trader";
        $individual->trading_name = "John's Goods";
        $individual->registered_address = $address;
        $individual->date_of_birth = $dateOfBirth;
        $individual->place_of_birth = $placeOfBirth;
        $individual->identification = $identification;

        $decoded = json_decode((new JsonSerializer())->serialize($individual), true);

        $this->assertSame(
            array(
                "first_name" => "John",
                "middle_name" => "Robert",
                "last_name" => "Trader",
                "trading_name" => "John's Goods",
                "registered_address" => array(
                    "address_line1" => "123 Main Street",
                    "address_line2" => "Suite 100",
                    "city" => "San Francisco",
                    "state" => "CA",
                    "zip" => "94105",
                    "country" => "US",
                ),
                "date_of_birth" => array("day" => 15, "month" => 1, "year" => 1990),
                "place_of_birth" => array("country" => "US"),
                "identification" => array("national_id_number" => "123456789"),
            ),
            $decoded
        );
    }
}
