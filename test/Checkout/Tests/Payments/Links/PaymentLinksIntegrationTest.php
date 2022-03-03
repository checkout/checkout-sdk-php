<?php

namespace Checkout\Tests\Payments\Links;

use Checkout\Common\Address;
use Checkout\Common\Country;
use Checkout\Common\Currency;
use Checkout\Common\CustomerRequest;
use Checkout\Common\Phone;
use Checkout\Common\Product;
use Checkout\Payments\BillingInformation;
use Checkout\Payments\Links\PaymentLinkRequest;
use Checkout\Payments\PaymentRecipient;
use Checkout\Payments\ProcessingSettings;
use Checkout\Payments\RiskRequest;
use Checkout\Payments\ShippingDetails;
use Checkout\Payments\ThreeDsRequest;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;

class PaymentLinksIntegrationTest extends SandboxTestFixture
{

    /**
     * @before
     */
    public function before(): void
    {
        $this->init(PlatformType::$default);
    }

    /**
     * @test
     */
    public function shouldCreateAndGetPaymentLink(): void
    {

        $request = $this->createPaymentLinkRequest();

        $response = $this->defaultApi->getPaymentLinksClient()->createPaymentLink($request);

        $this->assertResponse($response,
            "id",
            "reference",
            "expires_on",
            "_links",
            "_links.self",
            "_links.redirect",
        );

        $getResponse = $this->defaultApi->getPaymentLinksClient()->getPaymentLink($response["id"]);

        $this->assertResponse($getResponse,
            "id",
            "reference",
            "status",
            "amount",
            "billing",
            "currency",
            "billing",
            "customer",
            "created_on",
            "expires_on",
            "description",
            "products",
            "_links",
            "_links.self",
            "_links.redirect",
        );

    }

    private function createPaymentLinkRequest(): PaymentLinkRequest
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

        $paymentLinkRequest = new PaymentLinkRequest();
        $paymentLinkRequest->amount = 1000;
        $paymentLinkRequest->reference = "reference";
        $paymentLinkRequest->currency = Currency::$GBP;
        $paymentLinkRequest->description = "Payment for Gold Necklace";
        $paymentLinkRequest->customer = $customerRequest;
        $paymentLinkRequest->shipping = $shippingDetails;
        $paymentLinkRequest->billing = $billingInformation;
        $paymentLinkRequest->recipient = $recipient;
        $paymentLinkRequest->processing = $processing;
        $paymentLinkRequest->products = $products;
        $paymentLinkRequest->risk = $risk;
        $paymentLinkRequest->locale = "en-GB";
        $paymentLinkRequest->three_ds = $theeDsRequest;
        $paymentLinkRequest->capture = true;

        return $paymentLinkRequest;
    }

}
