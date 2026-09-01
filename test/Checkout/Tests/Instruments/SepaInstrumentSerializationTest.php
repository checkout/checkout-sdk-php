<?php

namespace Checkout\Tests\Instruments;

use Checkout\Common\Country;
use Checkout\Common\Currency;
use Checkout\Common\InstrumentType;
use Checkout\Instruments\BacsPaymentType;
use Checkout\Instruments\Create\CreateSepaAccountHolder;
use Checkout\Instruments\Create\CreateSepaBillingAddress;
use Checkout\Instruments\Create\CreateSepaInstrumentData;
use Checkout\Instruments\Create\CreateSepaInstrumentRequest;
use Checkout\Instruments\InstrumentAccountHolderType;
use Checkout\Instruments\SepaMandateType;
use Checkout\Instruments\SepaPaymentType;
use Checkout\Instruments\Update\UpdateSepaAccountHolder;
use Checkout\Instruments\Update\UpdateSepaBillingAddress;
use Checkout\Instruments\Update\UpdateSepaInstrumentData;
use Checkout\Instruments\Update\UpdateSepaInstrumentRequest;
use Checkout\JsonSerializer;
use Checkout\Payments\PaymentType;
use PHPUnit\Framework\TestCase;

/**
 * Schema validation tests for the SEPA instrument family, against the StoreSepaInstrumentRequest
 * and UpdateSepaInstrumentRequest swagger schemas.
 */
class SepaInstrumentSerializationTest extends TestCase
{
    private function serializer()
    {
        return new JsonSerializer();
    }

    /**
     * @test
     */
    public function shouldSerializeEveryPropertyOfTheStoreRequest()
    {
        $address = new CreateSepaBillingAddress();
        $address->address_line1 = "Evergreen Terrace";
        $address->address_line2 = "742";
        $address->city = "Paris";
        $address->zip = "75000";
        $address->country = Country::$FR;

        $holder = new CreateSepaAccountHolder();
        $holder->first_name = "John";
        $holder->last_name = "Wick";
        $holder->company_name = "Checkout.com";
        $holder->billing_address = $address;
        $holder->type = InstrumentAccountHolderType::$individual;

        $data = new CreateSepaInstrumentData();
        $data->type = SepaMandateType::$b2b;
        $data->account_number = "FR2810096000509685512959O86";
        $data->country = Country::$FR;
        $data->currency = Currency::$EUR;
        $data->payment_type = SepaPaymentType::$recurring;
        $data->mandate_id = "1234567890";
        $data->date_of_signature = "2020-01-01";

        $request = new CreateSepaInstrumentRequest();
        $request->instrument_data = $data;
        $request->account_holder = $holder;

        $decoded = json_decode($this->serializer()->serialize($request), true);

        $this->assertSame("sepa", $decoded["type"]);
        $this->assertSame("B2B", $decoded["instrument_data"]["type"]);
        $this->assertSame("FR2810096000509685512959O86", $decoded["instrument_data"]["account_number"]);
        $this->assertSame("FR", $decoded["instrument_data"]["country"]);
        $this->assertSame("EUR", $decoded["instrument_data"]["currency"]);
        $this->assertSame("recurring", $decoded["instrument_data"]["payment_type"]);
        $this->assertSame("1234567890", $decoded["instrument_data"]["mandate_id"]);
        $this->assertSame("2020-01-01", $decoded["instrument_data"]["date_of_signature"]);
        $this->assertSame("John", $decoded["account_holder"]["first_name"]);
        $this->assertSame("Wick", $decoded["account_holder"]["last_name"]);
        $this->assertSame("Checkout.com", $decoded["account_holder"]["company_name"]);
        $this->assertSame("individual", $decoded["account_holder"]["type"]);
        $this->assertSame("Evergreen Terrace", $decoded["account_holder"]["billing_address"]["address_line1"]);
        $this->assertSame("742", $decoded["account_holder"]["billing_address"]["address_line2"]);
        $this->assertSame("Paris", $decoded["account_holder"]["billing_address"]["city"]);
        $this->assertSame("75000", $decoded["account_holder"]["billing_address"]["zip"]);
        $this->assertSame("FR", $decoded["account_holder"]["billing_address"]["country"]);
    }

    /**
     * @test
     */
    public function shouldSerializeEveryPropertyOfTheUpdateRequest()
    {
        $address = new UpdateSepaBillingAddress();
        $address->address_line1 = "Evergreen Terrace";
        $address->address_line2 = "742";
        $address->city = "Paris";
        $address->zip = "75000";
        $address->country = Country::$FR;

        $holder = new UpdateSepaAccountHolder();
        $holder->first_name = "John";
        $holder->last_name = "Wick";
        $holder->billing_address = $address;

        $data = new UpdateSepaInstrumentData();
        $data->type = SepaMandateType::$core;
        $data->account_number = "FR2810096000509685512959O86";
        $data->country = Country::$FR;
        $data->currency = Currency::$EUR;
        $data->payment_type = SepaPaymentType::$regular;

        $request = new UpdateSepaInstrumentRequest();
        $request->instrument_data = $data;
        $request->account_holder = $holder;

        $decoded = json_decode($this->serializer()->serialize($request), true);

        $this->assertSame("sepa", $decoded["type"]);
        $this->assertSame("Core", $decoded["instrument_data"]["type"]);
        $this->assertSame("regular", $decoded["instrument_data"]["payment_type"]);
        $this->assertSame("Paris", $decoded["account_holder"]["billing_address"]["city"]);
        $this->assertSame(InstrumentType::$sepa, $request->type);
    }

    /**
     * @test
     */
    public function shouldKeepSepaLowercaseAndBacsCapitalized()
    {
        // The specification declares the SEPA payment_type lowercase and the Bacs Direct Debit
        // payment_type capitalized. This is the regression test that stops the two being unified.
        $this->assertSame("recurring", SepaPaymentType::$recurring);
        $this->assertSame("regular", SepaPaymentType::$regular);
        $this->assertSame("Recurring", BacsPaymentType::$recurring);
        $this->assertSame("Regular", BacsPaymentType::$regular);

        // Checkout\Payments\PaymentType serializes capitalized values and carries values neither
        // instrument schema allows, so it must not be used for either field.
        $this->assertNotSame(SepaPaymentType::$recurring, PaymentType::$recurring);
    }

    /**
     * @test
     */
    public function shouldNotReuseTheSharedAccountHolderOnTheSepaInstrument()
    {
        // StoreSepaInstrumentRequest.account_holder declares five properties. The shared
        // Checkout\Common\AccountHolder is a superset and must not be used here.
        $holder = new CreateSepaAccountHolder();
        $this->assertCount(5, get_object_vars($holder));
        $this->assertFalse(property_exists(CreateSepaAccountHolder::class, "phone"));
        $this->assertFalse(property_exists(CreateSepaAccountHolder::class, "identification"));
        $this->assertFalse(property_exists(CreateSepaAccountHolder::class, "date_of_birth"));
    }

    /**
     * @test
     */
    public function shouldNoLongerProvideTheSharedInstrumentDataClass()
    {
        $this->assertFalse(class_exists("Checkout\\Instruments\\Create\\InstrumentData"));
    }
}
