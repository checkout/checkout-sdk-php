<?php

namespace Checkout\Tests\Payments\Four;

use Checkout\CheckoutFourSdk;
use Checkout\Common\Address;
use Checkout\Common\Country;
use Checkout\Common\Currency;
use Checkout\Common\CustomerRequest;
use Checkout\Common\Four\Product;
use Checkout\Common\Phone;
use Checkout\Environment;
use Checkout\Payments\Four\Request\PaymentRequest;
use Checkout\Payments\Four\Request\Source\Apm\RequestIdealSource;
use Checkout\Payments\Four\Request\Source\Apm\RequestSofortSource;
use Checkout\Payments\Four\Request\Source\Apm\RequestTamaraSource;
use Checkout\Payments\ProcessingSettings;

class RequestApmPaymentsIntegrationTest extends AbstractPaymentsIntegrationTest
{
    /**
     * @test
     */
    public function shouldMakeIdealPayment()
    {
        $requestSource = new RequestIdealSource();
        $requestSource->bic = "INGBNL2A";
        $requestSource->description = "ORD50234E89";
        $requestSource->language = "nl";

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestSource;
        $paymentRequest->capture = true;
        $paymentRequest->amount = 1000;
        $paymentRequest->currency = Currency::$EUR;
        $paymentRequest->success_url = "https://testing.checkout.com/sucess";
        $paymentRequest->failure_url = "https://testing.checkout.com/failure";

        $paymentResponse1 = $this->retriable(
            function () use (&$paymentRequest) {
                return $this->fourApi->getPaymentsClient()->requestPayment($paymentRequest);
            });

        $this->assertResponse($paymentResponse1,
            "id",
            "status",
            "_links",
            "_links.self",
            "_links.redirect");

        $paymentResponse2 = $this->retriable(
            function () use (&$paymentResponse1) {
                return $this->fourApi->getPaymentsClient()->getPaymentDetails($paymentResponse1["id"]);
            });

        $this->assertResponse($paymentResponse2,
            "id",
            "requested_on",
            "source",
            "amount",
            //"balances",
            "currency",
            "payment_type",
            "status");
    }

    /**
     * @test
     */
    public function shouldMakeSofortPayment()
    {
        $requestSource = new RequestSofortSource();

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestSource;
        $paymentRequest->capture = true;
        $paymentRequest->amount = 100;
        $paymentRequest->currency = Currency::$EUR;
        $paymentRequest->success_url = "https://testing.checkout.com/sucess";
        $paymentRequest->failure_url = "https://testing.checkout.com/failure";

        $paymentResponse1 = $this->retriable(
            function () use (&$paymentRequest) {
                return $this->fourApi->getPaymentsClient()->requestPayment($paymentRequest);
            });

        $this->assertResponse($paymentResponse1,
            "id",
            "status",
            "_links",
            "_links.self",
            "_links.redirect");

        $paymentResponse2 = $this->retriable(
            function () use (&$paymentResponse1) {
                return $this->fourApi->getPaymentsClient()->getPaymentDetails($paymentResponse1["id"]);
            });

        $this->assertResponse($paymentResponse2,
            "id",
            "requested_on",
            "source",
            "amount",
            //"balances",
            "currency",
            "payment_type",
            "status");
    }

    /**
     * @test
     */
    public function shouldMakeTamaraPayment()
    {
        $this->markTestSkipped("preview");
        $builder = CheckoutFourSdk::oAuth();
        $builder->clientCredentials(getenv("CHECKOUT_FOUR_PREVIEW_OAUTH_CLIENT_ID"), getenv("CHECKOUT_FOUR_PREVIEW_OAUTH_CLIENT_SECRET"));
        $builder->setEnvironment(Environment::sandbox());
        $previewApi = $builder->build();

        $address = new Address();
        $address->address_line1 = "Cecilia Chapman";
        $address->address_line2 = "711-2880 Nulla St.";
        $address->city = "Mankato";
        $address->state = "Mississippi";
        $address->zip = "96522";
        $address->country = Country::$SA;

        $requestSource = new RequestTamaraSource();
        $requestSource->billing_address = $address;

        $processing = new ProcessingSettings();
        $processing->aft = true;
        $processing->shipping_amount = 1000;
        $processing->tax_amount = 500;

        $phone = new Phone();
        $phone->number = "113 496 0000";
        $phone->country_code = "+966";

        $customer = new CustomerRequest();
        $customer->name = "Cecilia Chapman";
        $customer->email = "c.chapman@example.com";
        $customer->phone = $phone;

        $product = new Product();
        $product->name = "Item name";
        $product->quantity = 3;
        $product->unit_price = 100;
        $product->total_amount = 100;
        $product->tax_amount = 19;
        $product->discount_amount = 2;
        $product->reference = "some description about item";
        $product->image_url = "https://some_s3bucket.com";
        $product->url = "https://some.website.com/item";
        $product->sku = "123687000111";

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestSource;
        $paymentRequest->capture = true;
        $paymentRequest->amount = 10000;
        $paymentRequest->currency = Currency::$SAR;
        $paymentRequest->success_url = "https://testing.checkout.com/sucess";
        $paymentRequest->failure_url = "https://testing.checkout.com/failure";
        $paymentRequest->reference = "ORD-5023-4E89";
        $paymentRequest->processing = $processing;
        $paymentRequest->processing_channel_id = "pc_zs5fqhybzc2e3jmq3efvybybpq";
        $paymentRequest->customer = $customer;
        $paymentRequest->items = array($product);

        $paymentResponse = $this->retriable(
            function () use (&$paymentRequest, &$previewApi) {
                return $previewApi->getPaymentsClient()->requestPayment($paymentRequest);
            });

        $this->assertResponse($paymentResponse,
            "id",
            "reference",
            "status",
            "_links",
            "customer",
            "customer.id",
            "customer.name",
            "customer.email",
            "customer.phone");

    }

}
