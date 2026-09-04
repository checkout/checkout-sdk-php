<?php

namespace Checkout\Tests\Instruments;

use Checkout\Common\AccountType;
use Checkout\Common\Country;
use Checkout\Common\Currency;
use Checkout\Common\InstrumentType;
use Checkout\Instruments\AchAccountType;
use Checkout\Instruments\Create\CreateAchAccountHolder;
use Checkout\Instruments\Create\CreateAchInstrumentData;
use Checkout\Instruments\Create\CreateAchInstrumentRequest;
use Checkout\Instruments\Create\CreateCustomerInstrumentRequest;
use Checkout\Instruments\InstrumentAccountHolderType;
use Checkout\Instruments\Update\UpdateAchAccountHolder;
use Checkout\Instruments\Update\UpdateAchInstrumentData;
use Checkout\Instruments\Update\UpdateAchInstrumentRequest;
use Checkout\JsonSerializer;
use PHPUnit\Framework\TestCase;

/**
 * Schema validation tests for the ACH instrument family, against the StoreAchInstrumentRequest and
 * UpdateAchInstrumentRequest swagger schemas.
 */
class AchInstrumentSerializationTest extends TestCase
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
        $data = new CreateAchInstrumentData();
        $data->account_type = AchAccountType::$checking;
        $data->account_number = "4099999992";
        $data->bank_code = "211370545";
        $data->currency = Currency::$USD;
        $data->country = Country::$US;

        $holder = new CreateAchAccountHolder();
        $holder->first_name = "John";
        $holder->last_name = "Smith";
        $holder->company_name = "Smith Enterprises";
        $holder->type = InstrumentAccountHolderType::$corporate;

        $customer = new CreateCustomerInstrumentRequest();
        $customer->id = "cus_y3oqhf46pyzuxjbcn2giaqnb44";

        $request = new CreateAchInstrumentRequest();
        $request->instrument_data = $data;
        $request->account_holder = $holder;
        $request->customer = $customer;

        $decoded = json_decode($this->serializer()->serialize($request), true);

        $this->assertSame("ach", $decoded["type"]);
        $this->assertSame("checking", $decoded["instrument_data"]["account_type"]);
        $this->assertSame("4099999992", $decoded["instrument_data"]["account_number"]);
        $this->assertSame("211370545", $decoded["instrument_data"]["bank_code"]);
        $this->assertSame("USD", $decoded["instrument_data"]["currency"]);
        $this->assertSame("US", $decoded["instrument_data"]["country"]);
        $this->assertCount(5, $decoded["instrument_data"]);
        $this->assertSame("John", $decoded["account_holder"]["first_name"]);
        $this->assertSame("Smith", $decoded["account_holder"]["last_name"]);
        $this->assertSame("Smith Enterprises", $decoded["account_holder"]["company_name"]);
        $this->assertSame("corporate", $decoded["account_holder"]["type"]);
        $this->assertCount(4, $decoded["account_holder"]);
        $this->assertSame("cus_y3oqhf46pyzuxjbcn2giaqnb44", $decoded["customer"]["id"]);
    }

    /**
     * @test
     */
    public function shouldOmitTheCustomerWhenUnset()
    {
        $request = new CreateAchInstrumentRequest();
        $request->instrument_data = new CreateAchInstrumentData();
        $request->instrument_data->account_type = AchAccountType::$savings;

        $decoded = json_decode($this->serializer()->serialize($request), true);

        $this->assertArrayNotHasKey("customer", $decoded);
        $this->assertSame("savings", $decoded["instrument_data"]["account_type"]);
    }

    /**
     * @test
     */
    public function shouldSerializeEveryPropertyOfTheUpdateRequest()
    {
        $data = new UpdateAchInstrumentData();
        $data->account_type = AchAccountType::$savings;
        $data->account_number = "4099999992";
        $data->bank_code = "211370545";
        $data->currency = Currency::$USD;
        $data->country = Country::$US;

        $holder = new UpdateAchAccountHolder();
        $holder->first_name = "John";
        $holder->last_name = "Smith";
        $holder->company_name = "Smith Enterprises";
        $holder->type = InstrumentAccountHolderType::$individual;

        $request = new UpdateAchInstrumentRequest();
        $request->instrument_data = $data;
        $request->account_holder = $holder;

        $decoded = json_decode($this->serializer()->serialize($request), true);

        $this->assertSame("ach", $decoded["type"]);
        $this->assertSame("savings", $decoded["instrument_data"]["account_type"]);
        $this->assertSame("211370545", $decoded["instrument_data"]["bank_code"]);
        $this->assertSame("individual", $decoded["account_holder"]["type"]);
        $this->assertSame("Smith Enterprises", $decoded["account_holder"]["company_name"]);
        $this->assertSame(InstrumentType::$ach, $request->type);
    }

    /**
     * @test
     */
    public function shouldMapTheAchAccountTypeValues()
    {
        $this->assertSame("savings", AchAccountType::$savings);
        $this->assertSame("checking", AchAccountType::$checking);

        // Checkout\Common\AccountType declares savings, current and cash. The ACH instrument field
        // declares savings and checking, so the two must not be shared.
        $this->assertSame("current", AccountType::$current);
        $this->assertFalse(property_exists(AchAccountType::class, "current"));
        $this->assertFalse(property_exists(AchAccountType::class, "cash"));
    }

    /**
     * @test
     */
    public function shouldNotReuseTheSharedAccountHolderOnTheAchInstrument()
    {
        // StoreAchInstrumentRequest.account_holder declares four properties and no billing address.
        $this->assertCount(4, get_object_vars(new CreateAchAccountHolder()));
        $this->assertCount(4, get_object_vars(new UpdateAchAccountHolder()));
        foreach ([CreateAchAccountHolder::class, UpdateAchAccountHolder::class] as $class) {
            $this->assertFalse(property_exists($class, "billing_address"));
            $this->assertFalse(property_exists($class, "phone"));
            $this->assertFalse(property_exists($class, "identification"));
        }
    }

    /**
     * @test
     */
    public function shouldCarryTheAchInstrumentType()
    {
        $this->assertSame("ach", InstrumentType::$ach);
        $this->assertSame(InstrumentType::$ach, (new CreateAchInstrumentRequest())->type);
        $this->assertSame(InstrumentType::$ach, (new UpdateAchInstrumentRequest())->type);
    }
}
