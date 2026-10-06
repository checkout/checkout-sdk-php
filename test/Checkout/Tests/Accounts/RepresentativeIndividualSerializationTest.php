<?php

namespace Checkout\Tests\Accounts;

use Checkout\Accounts\Citizenship;
use Checkout\Accounts\DateOfBirth;
use Checkout\Accounts\NationalIdType;
use Checkout\Accounts\PlaceOfBirth;
use Checkout\Accounts\RepresentativeIndividual;
use Checkout\Common\Address;
use Checkout\Common\Country;
use Checkout\Common\Phone;
use Checkout\JsonSerializer;
use PHPUnit\Framework\TestCase;

class RepresentativeIndividualSerializationTest extends TestCase
{
    public function testRepresentativeIndividualRoundTrip()
    {
        $dateOfBirth = new DateOfBirth();
        $dateOfBirth->day = 5;
        $dateOfBirth->month = 6;
        $dateOfBirth->year = 1996;

        $placeOfBirth = new PlaceOfBirth();
        $placeOfBirth->country = Country::$GB;

        $address = new Address();
        $address->address_line1 = "CheckoutSdk.com";
        $address->city = "London";
        $address->zip = "W1T 4TJ";
        $address->country = Country::$GB;

        $phone = new Phone();
        $phone->country_code = "GB";
        $phone->number = "2072343000";

        $citizenship = new Citizenship();
        $citizenship->type = "citizenship";
        $citizenship->country = Country::$GB;

        $individual = new RepresentativeIndividual();
        $individual->first_name = "John";
        $individual->middle_name = "Robert";
        $individual->last_name = "Representative";
        $individual->date_of_birth = $dateOfBirth;
        $individual->place_of_birth = $placeOfBirth;
        $individual->citizenships = array($citizenship);
        $individual->national_id_type = NationalIdType::$ssn;
        $individual->national_id_number = "123456789";
        $individual->email_address = "john@example.com";
        $individual->phone = $phone;
        $individual->address = $address;

        $decoded = json_decode((new JsonSerializer())->serialize($individual), true);

        $this->assertSame(
            array(
                "first_name" => "John",
                "middle_name" => "Robert",
                "last_name" => "Representative",
                "date_of_birth" => array("day" => 5, "month" => 6, "year" => 1996),
                "place_of_birth" => array("country" => "GB"),
                "citizenships" => array(array("type" => "citizenship", "country" => "GB")),
                "national_id_type" => "ssn",
                "national_id_number" => "123456789",
                "email_address" => "john@example.com",
                "phone" => array("country_code" => "GB", "number" => "2072343000"),
                "address" => array(
                    "address_line1" => "CheckoutSdk.com",
                    "city" => "London",
                    "zip" => "W1T 4TJ",
                    "country" => "GB",
                ),
            ),
            $decoded
        );
    }

    public function testNationalIdTypeValuesMatchSwagger()
    {
        $this->assertSame("ssn", NationalIdType::$ssn);
        $this->assertSame("itin", NationalIdType::$itin);
        $this->assertSame("passport", NationalIdType::$passport);
        $this->assertSame("driving_license", NationalIdType::$driving_license);
        $this->assertSame("national_id_card", NationalIdType::$national_id_card);
        $this->assertSame("residence_permit", NationalIdType::$residence_permit);
        $this->assertSame("other", NationalIdType::$other);
    }
}
