<?php

namespace Checkout\Tests\Payments\Setups;

use Checkout\JsonSerializer;
use Checkout\Payments\Setups\Common\BillingDescriptor\PaymentSetupBillingDescriptor;
use Checkout\Payments\Setups\Common\Order\AmountAllocationCommission;
use Checkout\Payments\Setups\Common\Order\Order;
use Checkout\Payments\Setups\Common\Order\PaymentSetupAmountAllocation;
use Checkout\Payments\Setups\Common\PaymentMethods\Bacs\Bacs;
use Checkout\Payments\Setups\Common\PaymentMethods\Bacs\BacsAccountHolder;
use Checkout\Payments\Setups\Common\PaymentMethods\Bacs\BacsAccountHolderType;
use Checkout\Payments\Setups\Common\PaymentMethods\CardPresent\CardPresent;
use Checkout\Payments\Setups\Common\PaymentMethods\PayByBank\PayByBank;
use Checkout\Payments\Setups\Common\PaymentMethods\PaymentMethods;
use Checkout\Payments\Setups\Common\PaymentMethods\Stablecoin\Stablecoin;
use Checkout\Payments\Setups\Common\PresentmentDetails\PaymentSetupPresentmentDetails;
use Checkout\Payments\Setups\Common\Terminal\PaymentSetupTerminal;
use Checkout\Payments\Setups\Request\PaymentSetupRequest;
use PHPUnit\Framework\TestCase;

class PaymentSetupFieldsSerializationTest extends TestCase
{
    public function testSerializesBillingDescriptorPresentmentDetailsAndTerminal()
    {
        $request = new PaymentSetupRequest();

        $request->billing_descriptor = new PaymentSetupBillingDescriptor();
        $request->billing_descriptor->name = "Checkout.com";
        $request->billing_descriptor->city = "London";
        $request->billing_descriptor->reference = "ref_123";

        $request->presentment_details = new PaymentSetupPresentmentDetails();
        $request->presentment_details->amount = 110;
        $request->presentment_details->currency = "GBP";

        $request->terminal = new PaymentSetupTerminal();
        $request->terminal->id = "12345678";
        $request->terminal->local_date_time = "2026-06-01T10:00:00Z";

        $decoded = json_decode((new JsonSerializer())->serialize($request), true);

        $this->assertSame("Checkout.com", $decoded['billing_descriptor']['name']);
        $this->assertSame("London", $decoded['billing_descriptor']['city']);
        $this->assertSame("ref_123", $decoded['billing_descriptor']['reference']);

        $this->assertSame(110, $decoded['presentment_details']['amount']);
        $this->assertSame("GBP", $decoded['presentment_details']['currency']);

        $this->assertSame("12345678", $decoded['terminal']['id']);
        $this->assertSame("2026-06-01T10:00:00Z", $decoded['terminal']['local_date_time']);
    }

    public function testSerializesNewPaymentMethodConfigs()
    {
        $bacs = new Bacs();
        $bacs->instrument_id = "src_test";
        $bacs->account_number = "12345678";
        $bacs->bank_code = "050389";
        $bacs->country = "GB";
        $bacs->currency = "GBP";
        $bacs->allow_partial_match = true;
        $bacs->account_holder = new BacsAccountHolder();
        $bacs->account_holder->type = BacsAccountHolderType::$individual;
        $bacs->account_holder->first_name = "John";

        $cardPresent = new CardPresent();
        $cardPresent->entry_mode = "chip";
        $cardPresent->store_for_future_use = true;

        $payByBank = new PayByBank();
        $payByBank->bank_id = "bank_123";

        $paymentMethods = new PaymentMethods();
        $paymentMethods->bacs = $bacs;
        $paymentMethods->card_present = $cardPresent;
        $paymentMethods->pay_by_bank = $payByBank;
        $paymentMethods->stablecoin = new Stablecoin();

        $request = new PaymentSetupRequest();
        $request->payment_methods = $paymentMethods;

        $decoded = json_decode((new JsonSerializer())->serialize($request), true);

        $this->assertSame("src_test", $decoded['payment_methods']['bacs']['instrument_id']);
        $this->assertSame("050389", $decoded['payment_methods']['bacs']['bank_code']);
        $this->assertSame("GB", $decoded['payment_methods']['bacs']['country']);
        $this->assertTrue($decoded['payment_methods']['bacs']['allow_partial_match']);
        $this->assertSame("individual", $decoded['payment_methods']['bacs']['account_holder']['type']);
        $this->assertSame("John", $decoded['payment_methods']['bacs']['account_holder']['first_name']);

        $this->assertSame("chip", $decoded['payment_methods']['card_present']['entry_mode']);
        $this->assertTrue($decoded['payment_methods']['card_present']['store_for_future_use']);

        $this->assertSame("bank_123", $decoded['payment_methods']['pay_by_bank']['bank_id']);

        $this->assertArrayHasKey('stablecoin', $decoded['payment_methods']);
    }

    public function testSerializesOrderAmountAllocationsAndScalars()
    {
        $commission = new AmountAllocationCommission();
        $commission->amount = 100;
        $commission->percentage = 1.5;

        $allocation = new PaymentSetupAmountAllocation();
        $allocation->id = "ent_test";
        $allocation->amount = 1000;
        $allocation->reference = "order_123";
        $allocation->commission = $commission;

        $order = new Order();
        $order->amount_allocations = [$allocation];
        $order->invoice_id = "inv_123";
        $order->shipping_amount = 50;
        $order->surcharge_amount = 10;
        $order->tax_amount = 200;
        $order->tipping_amount = 100;

        $request = new PaymentSetupRequest();
        $request->order = $order;

        $decoded = json_decode((new JsonSerializer())->serialize($request), true);

        $allocationJson = $decoded['order']['amount_allocations'][0];
        $this->assertSame("ent_test", $allocationJson['id']);
        $this->assertSame(1000, $allocationJson['amount']);
        $this->assertSame("order_123", $allocationJson['reference']);
        $this->assertSame(100, $allocationJson['commission']['amount']);
        $this->assertSame(1.5, $allocationJson['commission']['percentage']);

        $this->assertSame("inv_123", $decoded['order']['invoice_id']);
        $this->assertSame(50, $decoded['order']['shipping_amount']);
        $this->assertSame(10, $decoded['order']['surcharge_amount']);
        $this->assertSame(200, $decoded['order']['tax_amount']);
        $this->assertSame(100, $decoded['order']['tipping_amount']);
    }

    public function testUnsetFieldsAreOmittedFromSerialization()
    {
        $decoded = json_decode((new JsonSerializer())->serialize(new PaymentSetupRequest()), true);

        $this->assertArrayNotHasKey('billing_descriptor', $decoded);
        $this->assertArrayNotHasKey('presentment_details', $decoded);
        $this->assertArrayNotHasKey('terminal', $decoded);
    }
}
