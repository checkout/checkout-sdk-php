<?php

namespace Checkout\Tests\Payments\Hosted;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Common\Country;
use Checkout\Common\Currency;
use Checkout\Common\CustomerRequest;
use Checkout\Common\PaymentSourceType;
use Checkout\Common\Product;
use Checkout\Payments\BillingDescriptor;
use Checkout\Payments\BillingInformation;
use Checkout\Payments\CustomerSummary;
use Checkout\Payments\Hosted\HostedPaymentsSessionRequest;
use Checkout\Payments\PaymentRecipient;
use Checkout\Payments\PaymentType;
use Checkout\Payments\ProcessingSettings;
use Checkout\Payments\Request\PaymentInstruction;
use Checkout\Payments\Request\PaymentRetryRequest;
use Checkout\Payments\RiskRequest;
use Checkout\Payments\Sender\PaymentInstrumentSender;
use Checkout\Payments\ShippingDetails;
use Checkout\Payments\ThreeDsRequest;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;

class HostedPaymentsIntegrationTest extends SandboxTestFixture
{

    /**
     * @before
     * @throws CheckoutAuthorizationException
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    public function before(): void
    {
        $this->init(PlatformType::$default);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateAndGetHostedPaymentsPageDetails(): void
    {
        $request = $this->createHostedPaymentsRequest();

        $response = $this->checkoutApi->getHostedPaymentsClient()->createHostedPaymentsPageSession($request);

        $this->assertResponse(
            $response,
            "id",
            "reference",
            "_links",
            "_links.self",
            "_links.redirect"
        );

        $getResponse = $this->checkoutApi->getHostedPaymentsClient()->getHostedPaymentsPageDetails($response["id"]);

        $this->assertResponse(
            $getResponse,
            "id",
            "reference",
            "status",
            "amount",
            "billing",
            "currency",
            "customer",
            "description",
            "failure_url",
            "success_url",
            "cancel_url",
            "products",
            "_links",
            "_links.self",
            "_links.redirect"
        );
    }

    private function createHostedPaymentsRequest(): HostedPaymentsSessionRequest
    {
        $customerSummary = new CustomerSummary();
        $customerSummary->registration_date = "2023-05-01";
        $customerSummary->total_order_count = 5;
        $customerSummary->is_returning_customer = true;

        $customerRequest = new CustomerRequest();
        $customerRequest->email = $this->randomEmail();
        $customerRequest->name = "Customer";
        $customerRequest->summary = $customerSummary;

        $billingInformation = new BillingInformation();
        $billingInformation->address = $this->getAddress();
        $billingInformation->phone = $this->getPhone();

        $shippingDetails = new ShippingDetails();
        $shippingDetails->address = $this->getAddress();
        $shippingDetails->phone = $this->getPhone();

        $recipient = new PaymentRecipient();
        $recipient->account_number = "1234567";
        $recipient->dob = "1985-05-15";
        $recipient->first_name = "Mr.";
        $recipient->last_name = "Testing";
        $recipient->zip = "12345";
        $recipient->country = Country::$ES;

        $product = new Product();
        $product->name = "Gold Necklace";
        $product->quantity = 1;
        $product->price = 1000;

        $threeDsRequest = new ThreeDsRequest();
        $threeDsRequest->enabled = false;
        $threeDsRequest->attempt_n3d = false;

        $processing = new ProcessingSettings();
        $processing->aft = true;

        $risk = new RiskRequest();
        $risk->enabled = false;

        $billingDescriptor = new BillingDescriptor();
        $billingDescriptor->city = "London";
        $billingDescriptor->name = "Awesome name";

        $customerRetry = new PaymentRetryRequest();
        $customerRetry->max_attempts = 2;

        $instruction = new PaymentInstruction();
        $instruction->purpose = "fund";

        $hostedPaymentRequest = new HostedPaymentsSessionRequest();
        $hostedPaymentRequest->amount = 1000;
        $hostedPaymentRequest->reference = "reference";
        $hostedPaymentRequest->currency = Currency::$GBP;
        $hostedPaymentRequest->description = "Payment for Gold Necklace";
        $hostedPaymentRequest->display_name = "Merchant Name";
        $hostedPaymentRequest->customer = $customerRequest;
        $hostedPaymentRequest->shipping = $shippingDetails;
        $hostedPaymentRequest->billing = $billingInformation;
        $hostedPaymentRequest->recipient = $recipient;
        $hostedPaymentRequest->processing = $processing;
        $hostedPaymentRequest->products = array($product);
        $hostedPaymentRequest->risk = $risk;
        $hostedPaymentRequest->success_url = "https://example.com/payments/success";
        $hostedPaymentRequest->cancel_url = "https://example.com/payments/cancel";
        $hostedPaymentRequest->failure_url = "https://example.com/payments/failure";
        $hostedPaymentRequest->locale = "en-GB";
        $hostedPaymentRequest->three_ds = $threeDsRequest;
        $hostedPaymentRequest->capture = true;
        $hostedPaymentRequest->payment_type = PaymentType::$regular;
        $hostedPaymentRequest->billing_descriptor = $billingDescriptor;
        $hostedPaymentRequest->allow_payment_methods = array(PaymentSourceType::$card, PaymentSourceType::$ideal);
        $hostedPaymentRequest->customer_retry = $customerRetry;
        $hostedPaymentRequest->sender = new PaymentInstrumentSender();
        $hostedPaymentRequest->instruction = $instruction;

        return $hostedPaymentRequest;
    }

}
