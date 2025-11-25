<?php

namespace Checkout\Tests\Payments\Setups;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Common\Address;
use Checkout\Common\Country;
use Checkout\Common\Currency;
use Checkout\Common\Phone;
use Checkout\Payments\Setups\Request\PaymentSetupRequest;
use Checkout\Payments\Setups\Common\PaymentMethods\PaymentMethods;
use Checkout\Payments\Setups\Common\PaymentMethods\Klarna\Klarna;
use Checkout\Payments\Setups\Common\PaymentMethods\Klarna\KlarnaAccountHolder;
use Checkout\Payments\Setups\Common\Settings\Settings;
use Checkout\Payments\Setups\Common\Customer\Customer;
use Checkout\Payments\Setups\Common\Customer\Email;
use Checkout\Payments\Setups\Common\Customer\Device;
use Checkout\Payments\PaymentType;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;

class PaymentSetupsIntegrationTest extends SandboxTestFixture
{
    /**
     * @before
     * @throws CheckoutAuthorizationException
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    public function before()
    {
        $this->init(PlatformType::$default_oauth);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreatePaymentSetup()
    {
        // Arrange
        $request = $this->createPaymentSetupRequest();

        // Act
        $response = $this->checkoutApi->getPaymentSetupsClient()->createPaymentSetup($request);

        // Assert
        $this->assertNotNull($response);
        $this->assertNotNull($response["id"]);
        $this->assertEquals($request->processing_channel_id, $response["processing_channel_id"]);
        $this->assertEquals($request->amount, $response["amount"]);
        $this->assertEquals($request->currency, $response["currency"]);
        $this->assertEquals($request->payment_type, $response["payment_type"]);
        $this->assertEquals($request->reference, $response["reference"]);
        $this->assertEquals($request->description, $response["description"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUpdatePaymentSetup()
    {
        // Arrange
        $paymentSetupsRequest = $this->createPaymentSetupRequest();
        $createResponse = $this->checkoutApi->getPaymentSetupsClient()->createPaymentSetup($paymentSetupsRequest);

        $updateRequest = $this->createPaymentSetupRequest();
        $updateRequest->description = "Updated description";

        // Act
        $response = $this->checkoutApi->getPaymentSetupsClient()->updatePaymentSetup(
            $createResponse["id"],
            $updateRequest
        );

        // Assert
        $this->assertNotNull($response);
        $this->assertEquals($createResponse["id"], $response["id"]);
        $this->assertEquals("Updated description", $response["description"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetPaymentSetup()
    {
        // Arrange
        $paymentSetupsRequest = $this->createPaymentSetupRequest();
        $createResponse = $this->checkoutApi->getPaymentSetupsClient()->createPaymentSetup($paymentSetupsRequest);

        // Act
        $response = $this->checkoutApi->getPaymentSetupsClient()->getPaymentSetup($createResponse["id"]);

        // Assert
        $this->assertNotNull($response);
        $this->assertEquals($createResponse["id"], $response["id"]);
        $this->assertEquals($paymentSetupsRequest->processing_channel_id, $response["processing_channel_id"]);
        $this->assertEquals($paymentSetupsRequest->amount, $response["amount"]);
        $this->assertEquals($paymentSetupsRequest->currency, $response["currency"]);
        $this->assertEquals($paymentSetupsRequest->payment_type, $response["payment_type"]);
        $this->assertEquals($paymentSetupsRequest->reference, $response["reference"]);
        $this->assertEquals($paymentSetupsRequest->description, $response["description"]);
    }

    /**
     * @test
     * @group skip
     * @throws CheckoutApiException
     */
    public function shouldConfirmPaymentSetup()
    {
        $this->markTestSkipped("Integration test - requires valid payment method option");

        // Arrange
        $paymentSetupsRequest = $this->createPaymentSetupRequest();
        $createResponse = $this->checkoutApi->getPaymentSetupsClient()->createPaymentSetup($paymentSetupsRequest);

        $paymentMethodOptionId = "opt_test_12345";

        // Act
        $response = $this->checkoutApi->getPaymentSetupsClient()->confirmPaymentSetup(
            $createResponse["id"],
            $paymentMethodOptionId
        );

        // Assert
        $this->assertNotNull($response);
        $this->assertNotNull($response["id"]);
        $this->assertNotNull($response["action_id"]);
        $this->assertEquals($paymentSetupsRequest->amount, $response["amount"]);
        $this->assertEquals($paymentSetupsRequest->currency, $response["currency"]);
        $this->assertNotNull($response["processed_on"]);
    }

    /**
     * @return PaymentSetupRequest
     */
    private function createPaymentSetupRequest(): PaymentSetupRequest
    {
        $request = new PaymentSetupRequest();

        $request->processing_channel_id = getenv("CHECKOUT_PROCESSING_CHANNEL_ID");
        $request->amount = 1000;
        $request->currency = Currency::$GBP;
        $request->payment_type = PaymentType::$regular;
        $request->reference = "TEST-REF-" . substr(uniqid(), -6);
        $request->description = "Integration test payment setup";

        // Settings
        $settings = new Settings();
        $settings->success_url = "https://example.com/success";
        $settings->failure_url = "https://example.com/failure";
        $request->settings = $settings;

        // Customer
        $customer = new Customer();
        $customer->name = "John Smith";

        $email = new Email();
        $email->address = "john.smith+" . substr(uniqid(), -6) . "@example.com";
        $email->verified = true;
        $customer->email = $email;

        $phone = new Phone();
        $phone->country_code = "+44";
        $phone->number = "207 946 0000";
        $customer->phone = $phone;

        $device = new Device();
        $device->locale = "en_GB";
        $customer->device = $device;

        $request->customer = $customer;

        // Payment methods - Klarna example
        $paymentMethods = new PaymentMethods();

        $klarna = new Klarna();
        $klarna->initialization = "disabled";

        $accountHolder = new KlarnaAccountHolder();
        $billingAddress = new Address();
        $billingAddress->address_line1 = "123 High Street";
        $billingAddress->city = "London";
        $billingAddress->zip = "SW1A 1AA";
        $billingAddress->country = Country::$GB;
        $accountHolder->billing_address = $billingAddress;
        $klarna->account_holder = $accountHolder;

        $paymentMethods->klarna = $klarna;
        $request->payment_methods = $paymentMethods;

        return $request;
    }
}
