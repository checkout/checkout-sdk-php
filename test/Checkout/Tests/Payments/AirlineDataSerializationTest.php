<?php

namespace Checkout\Tests\Payments;

use Checkout\JsonSerializer;
use Checkout\Payments\AccommodationAddress;
use Checkout\Payments\AccommodationData;
use Checkout\Payments\AccommodationGuest;
use Checkout\Payments\AccommodationPhone;
use Checkout\Payments\AccommodationRoom;
use Checkout\Payments\AirlineData;
use Checkout\Payments\FlightLegDetails;
use Checkout\Payments\Passenger;
use Checkout\Payments\PassengerAddress;
use Checkout\Payments\ProcessingSettings;
use Checkout\Payments\Ticket;
use DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Serialization tests for the processing.airline_data and processing.accommodation_data sub-tree.
 *
 * The PHP SDK decodes responses with json_decode($body, true), so it cannot hit the typed
 * deserialization failure a merchant reported against another SDK. What it can get wrong is the
 * request: the serializer emits whatever shape the caller assigned, under the property name as
 * written. These tests pin the wire keys and the passenger cardinality.
 */
class AirlineDataSerializationTest extends TestCase
{
    /**
     * A single passenger must serialize as an object, not a one-element array.
     *
     * Verified against the sandbox on 2026-09-25: an object is accepted on every request surface,
     * an array only on POST /payments. hosted payments, payment links and payment contexts all
     * reject the array form. See AirlineData::$passenger for the matrix.
     */
    public function testSerializesASinglePassengerAsAnObject()
    {
        $airline = new AirlineData();
        $airline->ticket = new Ticket();
        $airline->ticket->number = "045-21351455613";
        $airline->passenger = new Passenger();
        $airline->passenger->first_name = "John";
        $airline->passenger->last_name = "White";

        $json = (new JsonSerializer())->serialize($airline);

        $this->assertStringContainsString('"passenger":{', $json);
        $this->assertStringNotContainsString('"passenger":[', $json);
    }

    /**
     * Several passengers can only be expressed as an array, which only POST /payments accepts.
     */
    public function testSerializesSeveralPassengersAsAnArray()
    {
        $first = new Passenger();
        $first->first_name = "John";
        $second = new Passenger();
        $second->first_name = "Jane";

        $airline = new AirlineData();
        $airline->passenger = array($first, $second);

        $json = (new JsonSerializer())->serialize($airline);

        $this->assertStringContainsString('"passenger":[{', $json);
    }

    /**
     * An empty array and a null are both rejected with
     * processing_airline_data_0_passenger_invalid, so an unset passenger must be absent from the
     * payload. The serializer drops nulls, which is what makes this work.
     */
    public function testOmitsPassengerWhenNotSet()
    {
        $airline = new AirlineData();
        $airline->ticket = new Ticket();
        $airline->ticket->number = "045";

        $json = (new JsonSerializer())->serialize($airline);

        $this->assertStringNotContainsString('passenger', $json);
    }

    /**
     * Every wire key the specification names, asserted on the serialized string so a future
     * rename cannot pass silently. class_of_travelling and stop_over_code are the two the other
     * SDKs had wrong; PHP already had them right and this keeps them that way.
     */
    public function testSerializesAirlineKeysExactlyAsTheSpecNamesThem()
    {
        $airline = new AirlineData();

        $airline->ticket = new Ticket();
        $airline->ticket->number = "045-21351455613";
        $airline->ticket->issue_date = new DateTime("2023-05-20");
        $airline->ticket->issuing_carrier_code = "AI";
        $airline->ticket->travel_package_indicator = "B";
        $airline->ticket->travel_agency_name = "World Tours";
        $airline->ticket->travel_agency_code = "01";

        $passenger = new Passenger();
        $passenger->first_name = "John";
        $passenger->last_name = "White";
        $passenger->date_of_birth = new DateTime("1990-05-26");
        $passenger->address = new PassengerAddress();
        $passenger->address->country = "US";
        $airline->passenger = $passenger;

        $leg = new FlightLegDetails();
        $leg->flight_number = "101";
        $leg->carrier_code = "BA";
        $leg->class_of_travelling = "J";
        $leg->departure_airport = "LHR";
        $leg->departure_date = new DateTime("2023-06-19");
        $leg->departure_time = "15:30";
        $leg->arrival_airport = "LAX";
        $leg->stop_over_code = "X";
        $leg->fare_basis_code = "SPRSVR";
        $airline->flight_leg_details = array($leg);

        $decoded = json_decode((new JsonSerializer())->serialize($airline), true);

        // Every property the fixture sets is asserted, so a wrong key on any of them fails here
        // rather than passing silently. Decoding beats substring matching for exactly that.
        $this->assertSame(
            array('number', 'issue_date', 'issuing_carrier_code', 'travel_package_indicator',
                'travel_agency_name', 'travel_agency_code'),
            array_keys($decoded['ticket'])
        );
        $this->assertSame("045-21351455613", $decoded['ticket']['number']);
        $this->assertSame("2023-05-20", $decoded['ticket']['issue_date']);
        $this->assertSame("AI", $decoded['ticket']['issuing_carrier_code']);
        $this->assertSame("B", $decoded['ticket']['travel_package_indicator']);
        $this->assertSame("World Tours", $decoded['ticket']['travel_agency_name']);
        $this->assertSame("01", $decoded['ticket']['travel_agency_code']);

        $this->assertSame(
            array('first_name', 'last_name', 'date_of_birth', 'address'),
            array_keys($decoded['passenger'])
        );
        $this->assertSame("John", $decoded['passenger']['first_name']);
        $this->assertSame("White", $decoded['passenger']['last_name']);
        $this->assertSame("1990-05-26", $decoded['passenger']['date_of_birth']);
        $this->assertSame(array('country' => 'US'), $decoded['passenger']['address']);

        $leg = $decoded['flight_leg_details'][0];
        $this->assertSame(
            array('flight_number', 'carrier_code', 'class_of_travelling', 'departure_date',
                'departure_time', 'departure_airport', 'arrival_airport', 'stop_over_code',
                'fare_basis_code'),
            array_keys($leg)
        );
        $this->assertSame("101", $leg['flight_number']);
        $this->assertSame("BA", $leg['carrier_code']);
        $this->assertSame("J", $leg['class_of_travelling']);
        $this->assertSame("2023-06-19", $leg['departure_date']);
        $this->assertSame("15:30", $leg['departure_time']);
        $this->assertSame("LHR", $leg['departure_airport']);
        $this->assertSame("LAX", $leg['arrival_airport']);
        $this->assertSame("X", $leg['stop_over_code']);
        $this->assertSame("SPRSVR", $leg['fare_basis_code']);

        // The key the API does not define.
        $this->assertArrayNotHasKey('service_class', $leg);
    }

    /**
     * $service_class is retained for backwards compatibility but the gateway discards it.
     */
    public function testStillSerializesTheDeprecatedServiceClassWhenSet()
    {
        $leg = new FlightLegDetails();
        $leg->service_class = "J";

        $json = (new JsonSerializer())->serialize($leg);

        $this->assertStringContainsString('"service_class":"J"', $json);
    }

    /**
     * property_phone and customer_service_phone were missing from AccommodationData entirely.
     */
    public function testSerializesTheFullAccommodationSubTree()
    {
        $accommodation = new AccommodationData();
        $accommodation->name = "The Sea View Hotel";
        $accommodation->booking_reference = "HOTEL123";
        $accommodation->check_in_date = new DateTime("2023-06-20");
        $accommodation->check_out_date = new DateTime("2023-06-23");
        $accommodation->address = new AccommodationAddress();
        $accommodation->address->address_line1 = "123 Beach Road";
        $accommodation->address->zip = "10001";
        $accommodation->state = "FL";
        $accommodation->country = "USA";
        $accommodation->city = "Los Angeles";
        $accommodation->number_of_rooms = 2;

        $guest = new AccommodationGuest();
        $guest->first_name = "Jane";
        $guest->last_name = "Doe";
        $guest->date_of_birth = new DateTime("1985-07-14");
        $accommodation->guests = array($guest);

        $room = new AccommodationRoom();
        $room->rate = "70";
        $room->number_of_nights_at_room_rate = "3";
        $accommodation->room = array($room);

        $propertyPhone = new AccommodationPhone();
        $propertyPhone->country_code = "44";
        $propertyPhone->number = "7123456789";
        $accommodation->property_phone = array($propertyPhone);

        $servicePhone = new AccommodationPhone();
        $servicePhone->country_code = "44";
        $servicePhone->number = "7987654321";
        $accommodation->customer_service_phone = array($servicePhone);

        $decoded = json_decode((new JsonSerializer())->serialize($accommodation), true);

        $this->assertSame(
            array('name', 'booking_reference', 'check_in_date', 'check_out_date', 'address',
                'state', 'country', 'city', 'number_of_rooms', 'guests', 'room',
                'property_phone', 'customer_service_phone'),
            array_keys($decoded)
        );

        $this->assertSame("The Sea View Hotel", $decoded['name']);
        $this->assertSame("HOTEL123", $decoded['booking_reference']);
        $this->assertSame("2023-06-20", $decoded['check_in_date']);
        $this->assertSame("2023-06-23", $decoded['check_out_date']);
        $this->assertSame(array('address_line1' => '123 Beach Road', 'zip' => '10001'), $decoded['address']);
        $this->assertSame("Los Angeles", $decoded['city']);
        $this->assertSame(2, $decoded['number_of_rooms']);

        // state and country are free-form strings: "FL" is a US state and "USA" is three letters,
        // so neither fits an ISO 3166-1 alpha-2 enum.
        $this->assertSame("FL", $decoded['state']);
        $this->assertSame("USA", $decoded['country']);

        $this->assertSame(
            array('first_name' => 'Jane', 'last_name' => 'Doe', 'date_of_birth' => '1985-07-14'),
            $decoded['guests'][0]
        );
        $this->assertSame(
            array('rate' => '70', 'number_of_nights_at_room_rate' => '3'),
            $decoded['room'][0]
        );
        $this->assertSame(array('country_code' => '44', 'number' => '7123456789'), $decoded['property_phone'][0]);
        $this->assertSame(array('country_code' => '44', 'number' => '7987654321'), $decoded['customer_service_phone'][0]);
    }

    /**
     * ProcessingSettings is the object hosted payments, payment links and POST /payments all
     * embed as "processing", so this covers every request surface that carries airline data.
     */
    public function testSerializesAirlineDataOnProcessingSettings()
    {
        $passenger = new Passenger();
        $passenger->first_name = "John";
        $passenger->last_name = "White";

        $airline = new AirlineData();
        $airline->ticket = new Ticket();
        $airline->ticket->number = "045";
        $airline->passenger = $passenger;

        $processing = new ProcessingSettings();
        $processing->airline_data = array($airline);

        $json = (new JsonSerializer())->serialize($processing);

        $this->assertStringContainsString('"airline_data":[{', $json);
        $this->assertStringContainsString('"passenger":{', $json);
        $this->assertStringNotContainsString('"passenger":[', $json);
    }
}
