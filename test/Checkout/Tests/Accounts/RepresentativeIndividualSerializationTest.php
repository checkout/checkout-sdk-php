<?php

namespace Checkout\Tests\Accounts;

use Checkout\Accounts\DateOfBirth;
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

        $individual = new RepresentativeIndividual();
        $individual->first_name = "John";
        $individual->middle_name = "Robert";
        $individual->last_name = "Representative";
        $individual->date_of_birth = $dateOfBirth;
        $individual->place_of_birth = $placeOfBirth;
        $individual->national_id_number = "123456789";
        $individual->email_address = "john@example.com";
        $individual->phone = $phone;
        $individual->address = $address;

        $decoded = json_decode((new JsonSerializer())->serialize($individual), true);

        $this->assertSame("John", $decoded['first_name']);
        $this->assertSame("Robert", $decoded['middle_name']);
        $this->assertSame("Representative", $decoded['last_name']);
        $this->assertSame("123456789", $decoded['national_id_number']);
        $this->assertSame("john@example.com", $decoded['email_address']);
        $this->assertSame(1996, $decoded['date_of_birth']['year']);
        $this->assertSame("GB", $decoded['place_of_birth']['country']);
        $this->assertSame("GB", $decoded['phone']['country_code']);
        $this->assertSame("W1T 4TJ", $decoded['address']['zip']);
    }
}
