<?php

namespace Checkout\Tests\Payments\Setups;

use Checkout\CheckoutApiException;
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
use Checkout\Payments\Setups\PaymentSetupsClient;
use Checkout\Payments\Setups\Request\PaymentSetupRequest;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class PaymentSetupsClientTest extends UnitTestFixture
{
    /**
     * @var PaymentSetupsClient
     */
    private $client;

    /**
     * @before
     */
    public function init()
    {
        $this->initMocks(PlatformType::$default);
        $this->client = new PaymentSetupsClient($this->apiClient, $this->configuration);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreatePaymentSetup()
    {
        $this->apiClient
            ->method("post")
            ->willReturn(["response"]);

        $response = $this->client->createPaymentSetup(new PaymentSetupRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUpdatePaymentSetup()
    {
        $paymentSetupId = "setup_123456";

        $this->apiClient
            ->method("put")
            ->with(
                $this->equalTo("payments/setups/" . $paymentSetupId),
                $this->isInstanceOf(PaymentSetupRequest::class),
                $this->anything()
            )
            ->willReturn(["response"]);

        $response = $this->client->updatePaymentSetup($paymentSetupId, new PaymentSetupRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetPaymentSetup()
    {
        $paymentSetupId = "setup_123456";

        $this->apiClient
            ->method("get")
            ->with(
                $this->equalTo("payments/setups/" . $paymentSetupId),
                $this->anything()
            )
            ->willReturn(["response"]);

        $response = $this->client->getPaymentSetup($paymentSetupId);
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldConfirmPaymentSetup()
    {
        $paymentSetupId = "setup_123456";
        $paymentMethodOptionId = "opt_123456";

        $this->apiClient
            ->method("post")
            ->with(
                $this->equalTo("payments/setups/" . $paymentSetupId . "/confirm/" . $paymentMethodOptionId),
                $this->isNull(),
                $this->anything(),
                $this->isNull()
            )
            ->willReturn(["response"]);

        $response = $this->client->confirmPaymentSetup($paymentSetupId, $paymentMethodOptionId);
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreatePaymentSetupWithNewFields()
    {
        $bacs = new Bacs();
        $bacs->instrument_id = "src_test";
        $bacs->country = "GB";
        $bacs->currency = "GBP";
        $bacs->allow_partial_match = true;
        $bacs->account_holder = new BacsAccountHolder();
        $bacs->account_holder->type = BacsAccountHolderType::$individual;
        $bacs->account_holder->first_name = "John";

        $paymentMethods = new PaymentMethods();
        $paymentMethods->bacs = $bacs;
        $paymentMethods->card_present = new CardPresent();
        $paymentMethods->pay_by_bank = new PayByBank();
        $paymentMethods->stablecoin = new Stablecoin();

        $allocation = new PaymentSetupAmountAllocation();
        $allocation->id = "ent_test";
        $allocation->amount = 1000;
        $allocation->commission = new AmountAllocationCommission();
        $allocation->commission->amount = 100;
        $allocation->commission->percentage = 1.5;

        $order = new Order();
        $order->amount_allocations = [$allocation];
        $order->tax_amount = 200;
        $order->tipping_amount = 100;

        $request = new PaymentSetupRequest();
        $request->payment_methods = $paymentMethods;
        $request->order = $order;
        $request->billing_descriptor = new PaymentSetupBillingDescriptor();
        $request->billing_descriptor->name = "Checkout.com";
        $request->presentment_details = new PaymentSetupPresentmentDetails();
        $request->presentment_details->amount = 110;
        $request->terminal = new PaymentSetupTerminal();
        $request->terminal->id = "12345678";

        $this->assertEquals("individual", $request->payment_methods->bacs->account_holder->type);
        $this->assertInstanceOf(CardPresent::class, $request->payment_methods->card_present);
        $this->assertInstanceOf(PayByBank::class, $request->payment_methods->pay_by_bank);
        $this->assertInstanceOf(Stablecoin::class, $request->payment_methods->stablecoin);
        $this->assertEquals("ent_test", $request->order->amount_allocations[0]->id);
        $this->assertEquals(1.5, $request->order->amount_allocations[0]->commission->percentage);
        $this->assertEquals(200, $request->order->tax_amount);
        $this->assertEquals("Checkout.com", $request->billing_descriptor->name);
        $this->assertEquals(110, $request->presentment_details->amount);
        $this->assertEquals("12345678", $request->terminal->id);
    }
}
