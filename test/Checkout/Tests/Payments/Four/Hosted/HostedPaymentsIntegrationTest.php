<?php

namespace Checkout\Tests\Payments\Four\Hosted;

use Checkout\CheckoutApiException;
use Checkout\CheckoutAuthorizationException;
use Checkout\Common\Address;
use Checkout\Common\Country;
use Checkout\Common\Currency;
use Checkout\Common\CustomerRequest;
use Checkout\Common\PaymentSourceType;
use Checkout\Common\Phone;
use Checkout\Common\Product;
use Checkout\Payments\BillingDescriptor;
use Checkout\Payments\BillingInformation;
use Checkout\Payments\Hosted\HostedPaymentsSessionRequest;
use Checkout\Payments\PaymentRecipient;
use Checkout\Payments\PaymentType;
use Checkout\Payments\ProcessingSettings;
use Checkout\Payments\RiskRequest;
use Checkout\Payments\ShippingDetails;
use Checkout\Payments\ThreeDsRequest;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;

class HostedPaymentsIntegrationTest extends SandboxTestFixture
{

    /**
     * @before
     * @throws CheckoutAuthorizationException
     */
    public function before(): void
    {
        $this->init(PlatformType::$four);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateAndGetHostedPaymentsPageDetails(): void
    {

        $request = $this->createHostedPaymentsRequest();

        $response = $this->fourApi->getHostedPaymentsClient()->createHostedPaymentsPageSession($request);

        $this->assertResponse($response,
            "id",
            "reference",
            "_links",
            "_links.self",
            "_links.redirect",
            "warnings"
        );
        foreach ($response["warnings"] as $warning) {
            $this->assertResponse($warning,
                "code",
                "value",
                "description");
        }

        $getResponse = $this->fourApi->getHostedPaymentsClient()->getHostedPaymentsPageDetails($response["id"]);

        $this->assertResponse($getResponse,
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
            "_links.redirect",
        );

    }

    private function createHostedPaymentsRequest(): HostedPaymentsSessionRequest
    {
        $phone = new Phone();
        $phone->country_code = "44";
        $phone->number = "020 222333";

        $address = new Address();
        $address->address_line1 = "CheckoutSdk.com";
        $address->address_line2 = "90 Tottenham Court Road";
        $address->city = "London";
        $address->state = "London";
        $address->zip = "W1T 4TJ";
        $address->country = Country::$GB;

        $customerRequest = new CustomerRequest();
        $customerRequest->email = $this->randomEmail();
        $customerRequest->name = "Customer";

        $billingInformation = new BillingInformation();
        $billingInformation->address = $address;
        $billingInformation->phone = $phone;

        $shippingDetails = new ShippingDetails();
        $shippingDetails->address = $address;
        $shippingDetails->phone = $phone;

        $recipient = new PaymentRecipient();
        $recipient->account_number = "1234567";
        $recipient->country = Country::$ES;
        $recipient->dob = "1985-05-15";
        $recipient->first_name = "IT";
        $recipient->last_name = "Testing";
        $recipient->zip = "12345";

        $product = new Product();
        $product->name = "Gold Necklace";
        $product->quantity = 1;
        $product->price = 10;

        $products = array($product);

        $theeDsRequest = new ThreeDsRequest();
        $theeDsRequest->enabled = false;
        $theeDsRequest->attempt_n3d = false;

        $processing = new ProcessingSettings();
        $processing->aft = true;

        $risk = new RiskRequest();
        $risk->enabled = false;

        $billingDescriptor = new BillingDescriptor();
        $billingDescriptor->city = "London";
        $billingDescriptor->name = "Awesome name";

        $hostedPaymentRequest = new HostedPaymentsSessionRequest();
        $hostedPaymentRequest->amount = 1000;
        $hostedPaymentRequest->reference = "reference";
        $hostedPaymentRequest->currency = Currency::$GBP;
        $hostedPaymentRequest->description = "Payment for Gold Necklace";
        $hostedPaymentRequest->customer = $customerRequest;
        $hostedPaymentRequest->shipping = $shippingDetails;
        $hostedPaymentRequest->billing = $billingInformation;
        $hostedPaymentRequest->recipient = $recipient;
        $hostedPaymentRequest->processing = $processing;
        $hostedPaymentRequest->products = $products;
        $hostedPaymentRequest->risk = $risk;
        $hostedPaymentRequest->success_url = "https://example.com/payments/success";
        $hostedPaymentRequest->cancel_url = "https://example.com/payments/cancel";
        $hostedPaymentRequest->failure_url = "https://example.com/payments/failure";
        $hostedPaymentRequest->locale = "en-GB";
        $hostedPaymentRequest->three_ds = $theeDsRequest;
        $hostedPaymentRequest->capture = true;
        $hostedPaymentRequest->payment_type = PaymentType::$regular;
        $hostedPaymentRequest->billing_descriptor = $billingDescriptor;
        $hostedPaymentRequest->allow_payment_methods = array(PaymentSourceType::$card, PaymentSourceType::$ideal);

        return $hostedPaymentRequest;
    }

}
