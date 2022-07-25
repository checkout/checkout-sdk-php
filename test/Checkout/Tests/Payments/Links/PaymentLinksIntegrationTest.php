<?php

namespace Checkout\Tests\Payments\Links;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Common\Currency;
use Checkout\Common\CustomerRequest;
use Checkout\Common\PaymentSourceType;
use Checkout\Common\Product;
use Checkout\Payments\BillingDescriptor;
use Checkout\Payments\BillingInformation;
use Checkout\Payments\Links\PaymentLinkRequest;
use Checkout\Payments\PaymentRecipient;
use Checkout\Payments\PaymentType;
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
     * @throws CheckoutAuthorizationException
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    public function before()
    {
        $this->init(PlatformType::$default);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateAndGetPaymentLink()
    {
        $request = $this->createPaymentLinkRequest();

        $response = $this->checkoutApi->getPaymentLinksClient()->createPaymentLink($request);

        $this->assertResponse(
            $response,
            "id",
            "reference",
            "expires_on",
            "_links",
            "_links.self",
            "_links.redirect",
            "warnings"
        );
        foreach ($response["warnings"] as $warning) {
            $this->assertResponse(
                $warning,
                "code",
                "value",
                "description"
            );
        }

        $getResponse = $this->checkoutApi->getPaymentLinksClient()->getPaymentLink($response["id"]);

        $this->assertResponse(
            $getResponse,
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
            "_links.redirect"
        );
    }

    private function createPaymentLinkRequest()
    {
        $customerRequest = new CustomerRequest();
        $customerRequest->email = $this->randomEmail();
        $customerRequest->name = "Customer";

        $billingInformation = new BillingInformation();
        $billingInformation->address = $this->getAddress();
        $billingInformation->phone = $this->getPhone();

        $shippingDetails = new ShippingDetails();
        $shippingDetails->address = $this->getAddress();
        $shippingDetails->phone = $this->getPhone();

        $recipient = new PaymentRecipient();
        $recipient->account_number = "1234567";
        $recipient->dob = "1985-05-15";
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

        $paymentLinkRequest = new PaymentLinkRequest();
        $paymentLinkRequest->amount = 200;
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
        $paymentLinkRequest->expires_in = 604800;
        $paymentLinkRequest->payment_type = PaymentType::$regular;
        $paymentLinkRequest->billing_descriptor = $billingDescriptor;
        $paymentLinkRequest->allow_payment_methods = array(PaymentSourceType::$card, PaymentSourceType::$ideal);

        return $paymentLinkRequest;
    }

}
