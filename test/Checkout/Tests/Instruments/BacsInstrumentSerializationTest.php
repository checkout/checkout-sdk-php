<?php

namespace Checkout\Tests\Instruments;

use Checkout\Common\Country;
use Checkout\Common\Currency;
use Checkout\Common\InstrumentType;
use Checkout\Instruments\BacsPaymentType;
use Checkout\Instruments\Create\CreateBacsAccountHolder;
use Checkout\Instruments\Create\CreateBacsBillingAddress;
use Checkout\Instruments\Create\CreateBacsInstrumentAccount;
use Checkout\Instruments\Create\CreateBacsInstrumentData;
use Checkout\Instruments\Create\CreateBacsInstrumentRequest;
use Checkout\Instruments\Create\CreateCustomerInstrumentRequest;
use Checkout\Instruments\InstrumentAccountHolderType;
use Checkout\Instruments\Update\UpdateBacsAccountHolder;
use Checkout\Instruments\Update\UpdateBacsBillingAddress;
use Checkout\Instruments\Update\UpdateBacsInstrumentData;
use Checkout\Instruments\Update\UpdateBacsInstrumentRequest;
use Checkout\JsonSerializer;
use PHPUnit\Framework\TestCase;

/**
 * Schema validation tests for the Bacs Direct Debit instrument family, against the
 * StoreBacsInstrumentRequest and UpdateBacsInstrumentRequest swagger schemas.
 */
class BacsInstrumentSerializationTest extends TestCase
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
        $address = new CreateBacsBillingAddress();
        $address->address_line1 = "Cloverfield St.";
        $address->address_line2 = "23A";
        $address->city = "London";
        $address->zip = "SW1A 1AA";
        $address->country = Country::$GB;

        $holder = new CreateBacsAccountHolder();
        $holder->first_name = "John";
        $holder->last_name = "Smith";
        $holder->billing_address = $address;

        $account = new CreateBacsInstrumentAccount();
        $account->processing_channel_id = "pc_q4dbxom5jbgudnjzjpz7j2z6uq";

        $data = new CreateBacsInstrumentData();
        $data->account_number = "86753246";
        $data->bank_code = "040004";
        $data->country = Country::$GB;
        $data->currency = Currency::$GBP;
        $data->payment_type = BacsPaymentType::$recurring;
        $data->allow_partial_match = false;

        $customer = new CreateCustomerInstrumentRequest();
        $customer->id = "cus_y3oqhf46pyzuxjbcn2giaqnb44";

        $request = new CreateBacsInstrumentRequest();
        $request->account = $account;
        $request->instrument_data = $data;
        $request->account_holder = $holder;
        $request->customer = $customer;

        $decoded = json_decode($this->serializer()->serialize($request), true);

        $this->assertSame("bacs", $decoded["type"]);
        $this->assertSame("pc_q4dbxom5jbgudnjzjpz7j2z6uq", $decoded["account"]["processing_channel_id"]);
        $this->assertSame("86753246", $decoded["instrument_data"]["account_number"]);
        $this->assertSame("040004", $decoded["instrument_data"]["bank_code"]);
        $this->assertSame("GB", $decoded["instrument_data"]["country"]);
        $this->assertSame("GBP", $decoded["instrument_data"]["currency"]);
        $this->assertSame("Recurring", $decoded["instrument_data"]["payment_type"]);
        $this->assertFalse($decoded["instrument_data"]["allow_partial_match"]);
        $this->assertSame("John", $decoded["account_holder"]["first_name"]);
        $this->assertSame("Smith", $decoded["account_holder"]["last_name"]);
        $this->assertSame("Cloverfield St.", $decoded["account_holder"]["billing_address"]["address_line1"]);
        $this->assertSame("23A", $decoded["account_holder"]["billing_address"]["address_line2"]);
        $this->assertSame("London", $decoded["account_holder"]["billing_address"]["city"]);
        $this->assertSame("SW1A 1AA", $decoded["account_holder"]["billing_address"]["zip"]);
        $this->assertSame("GB", $decoded["account_holder"]["billing_address"]["country"]);
        $this->assertSame("cus_y3oqhf46pyzuxjbcn2giaqnb44", $decoded["customer"]["id"]);
    }

    /**
     * @test
     */
    public function shouldSerializeEveryPropertyOfTheUpdateRequest()
    {
        $address = new UpdateBacsBillingAddress();
        $address->address_line1 = "Cloverfield St.";
        $address->address_line2 = "23A";
        $address->city = "London";
        $address->zip = "SW1A 1AA";
        $address->country = Country::$GB;

        $holder = new UpdateBacsAccountHolder();
        $holder->first_name = "John";
        $holder->last_name = "Smith";
        $holder->company_name = "Wayne Enterprises";
        $holder->billing_address = $address;
        $holder->type = InstrumentAccountHolderType::$corporate;

        $data = new UpdateBacsInstrumentData();
        $data->account_number = "86753246";
        $data->bank_code = "040004";
        $data->country = Country::$GB;
        $data->currency = Currency::$GBP;
        $data->payment_type = BacsPaymentType::$regular;
        $data->allow_partial_match = true;

        $request = new UpdateBacsInstrumentRequest();
        $request->instrument_data = $data;
        $request->account_holder = $holder;

        $decoded = json_decode($this->serializer()->serialize($request), true);

        $this->assertSame("bacs", $decoded["type"]);
        $this->assertSame("86753246", $decoded["instrument_data"]["account_number"]);
        $this->assertSame("040004", $decoded["instrument_data"]["bank_code"]);
        $this->assertSame("GB", $decoded["instrument_data"]["country"]);
        $this->assertSame("GBP", $decoded["instrument_data"]["currency"]);
        $this->assertSame("Regular", $decoded["instrument_data"]["payment_type"]);
        $this->assertTrue($decoded["instrument_data"]["allow_partial_match"]);
        $this->assertSame("John", $decoded["account_holder"]["first_name"]);
        $this->assertSame("Smith", $decoded["account_holder"]["last_name"]);
        $this->assertSame("Wayne Enterprises", $decoded["account_holder"]["company_name"]);
        $this->assertSame("corporate", $decoded["account_holder"]["type"]);
        $this->assertSame("London", $decoded["account_holder"]["billing_address"]["city"]);
    }

    /**
     * @test
     */
    public function shouldSetTheBacsInstrumentTypeFromTheConstructor()
    {
        $this->assertSame(InstrumentType::$bacs, (new CreateBacsInstrumentRequest())->type);
        $this->assertSame(InstrumentType::$bacs, (new UpdateBacsInstrumentRequest())->type);
        $this->assertSame("bacs", InstrumentType::$bacs);
    }

    /**
     * @test
     */
    public function shouldNotExposeAnAccountHolderTypeOnTheStoreShape()
    {
        // StoreBacsInstrumentRequest.account_holder declares first_name, last_name and
        // billing_address only.
        $this->assertFalse(property_exists(CreateBacsAccountHolder::class, "type"));
        $this->assertFalse(property_exists(CreateBacsAccountHolder::class, "company_name"));
        $this->assertTrue(property_exists(UpdateBacsAccountHolder::class, "type"));
        $this->assertTrue(property_exists(UpdateBacsAccountHolder::class, "company_name"));
    }
}
