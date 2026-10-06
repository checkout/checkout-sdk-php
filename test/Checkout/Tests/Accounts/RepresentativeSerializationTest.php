<?php

namespace Checkout\Tests\Accounts;

use Checkout\Accounts\DateOfBirth;
use Checkout\Accounts\EntityRoles;
use Checkout\Accounts\Identification;
use Checkout\Accounts\PlaceOfBirth;
use Checkout\Accounts\Representative;
use Checkout\Common\Address;
use Checkout\Common\Country;
use Checkout\Common\Phone;
use Checkout\JsonSerializer;
use PHPUnit\Framework\TestCase;

/**
 * company.representatives[] of the Company (2.0) variants: the person fields sit flat on the
 * representative, and phone is number only.
 */
class RepresentativeSerializationTest extends TestCase
{
    public function testV2RepresentativeFlatFieldsRoundTrip()
    {
        $address = new Address();
        $address->address_line1 = "123 Main Street";
        $address->city = "San Francisco";
        $address->state = "CA";
        $address->zip = "94105";
        $address->country = Country::$US;

        $identification = new Identification();
        $identification->national_id_number = "123456789";

        $phone = new Phone();
        $phone->number = "4155678901";

        $dateOfBirth = new DateOfBirth();
        $dateOfBirth->day = 15;
        $dateOfBirth->month = 1;
        $dateOfBirth->year = 1990;

        $placeOfBirth = new PlaceOfBirth();
        $placeOfBirth->country = Country::$US;

        $representative = new Representative();
        $representative->id = "rep_l2wbgkhgxsoeppr5ffaplhhbh4";
        $representative->first_name = "John";
        $representative->last_name = "Representative";
        $representative->address = $address;
        $representative->identification = $identification;
        $representative->phone = $phone;
        $representative->date_of_birth = $dateOfBirth;
        $representative->place_of_birth = $placeOfBirth;
        $representative->roles = array(EntityRoles::$ubo);

        $decoded = json_decode((new JsonSerializer())->serialize($representative), true);

        $this->assertMatchesRegularExpression('/^rep_[a-z0-9]{26}$/', $decoded['id']);
        $this->assertSame(
            array(
                "id" => "rep_l2wbgkhgxsoeppr5ffaplhhbh4",
                "roles" => array("ubo"),
                "first_name" => "John",
                "last_name" => "Representative",
                "address" => array(
                    "address_line1" => "123 Main Street",
                    "city" => "San Francisco",
                    "state" => "CA",
                    "zip" => "94105",
                    "country" => "US",
                ),
                "identification" => array("national_id_number" => "123456789"),
                "phone" => array("number" => "4155678901"),
                "date_of_birth" => array("day" => 15, "month" => 1, "year" => 1990),
                "place_of_birth" => array("country" => "US"),
            ),
            $decoded
        );
    }
}
