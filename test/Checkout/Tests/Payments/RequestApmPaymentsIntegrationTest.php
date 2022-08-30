<?php

namespace Checkout\Tests\Payments;

use Checkout\CheckoutApiException;
use Checkout\CheckoutSdk;
use Checkout\Common\Address;
use Checkout\Common\Country;
use Checkout\Common\Currency;
use Checkout\Common\CustomerRequest;
use Checkout\Common\Phone;
use Checkout\Common\Product;
use Checkout\Environment;
use Checkout\Payments\ProcessingSettings;
use Checkout\Payments\Request\PaymentRequest;
use Checkout\Payments\Request\Source\Apm\RequestAfterPaySource;
use Checkout\Payments\Request\Source\Apm\RequestAlipayPlusSource;
use Checkout\Payments\Request\Source\Apm\RequestBenefitSource;
use Checkout\Payments\Request\Source\Apm\RequestGiropaySource;
use Checkout\Payments\Request\Source\Apm\RequestIdealSource;
use Checkout\Payments\Request\Source\Apm\RequestMbwaySource;
use Checkout\Payments\Request\Source\Apm\RequestPayPalSource;
use Checkout\Payments\Request\Source\Apm\RequestQPaySource;
use Checkout\Payments\Request\Source\Apm\RequestSofortSource;
use Checkout\Payments\Request\Source\Apm\RequestTamaraSource;
use Exception;

class RequestApmPaymentsIntegrationTest extends AbstractPaymentsIntegrationTest
{
    /**
     * @test
     */
    public function shouldMakeAliPayPayment()
    {
        $requestSource = RequestAlipayPlusSource::requestAlipayPlusCNSource();

        $paymentRequest = new PaymentRequest();
        $paymentRequest->reference = $this->randomEmail();
        $paymentRequest->source = $requestSource;
        $paymentRequest->capture = true;
        $paymentRequest->amount = 1000;
        $paymentRequest->currency = Currency::$EUR;
        $paymentRequest->success_url = "https://testing.checkout.com/sucess";
        $paymentRequest->failure_url = "https://testing.checkout.com/failure";

        try {
            $this->checkoutApi->getPaymentsClient()->requestPayment($paymentRequest);
        } catch (Exception $ex) {
            self::assertTrue($ex instanceof CheckoutApiException);
        }
    }

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
                return $this->checkoutApi->getPaymentsClient()->requestPayment($paymentRequest);
            }
        );

        $this->assertResponse(
            $paymentResponse1,
            "id",
            "status",
            "_links",
            "_links.self"
        );

        $paymentResponse2 = $this->retriable(
            function () use (&$paymentResponse1) {
                return $this->checkoutApi->getPaymentsClient()->getPaymentDetails($paymentResponse1["id"]);
            }
        );

        $this->assertResponse(
            $paymentResponse2,
            "id",
            "requested_on",
            "source",
            "amount",
            //"balances",
            "currency",
            "payment_type",
            "status"
        );
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
                return $this->checkoutApi->getPaymentsClient()->requestPayment($paymentRequest);
            }
        );

        $this->assertResponse(
            $paymentResponse1,
            "id",
            "status",
            "_links",
            "_links.self",
            "_links.redirect"
        );

        $paymentResponse2 = $this->retriable(
            function () use (&$paymentResponse1) {
                return $this->checkoutApi->getPaymentsClient()->getPaymentDetails($paymentResponse1["id"]);
            }
        );

        $this->assertResponse(
            $paymentResponse2,
            "id",
            "requested_on",
            "source",
            "amount",
            //"balances",
            "currency",
            "payment_type",
            "status"
        );
    }

    /**
     * @test
     */
    public function shouldMakeTamaraPayment()
    {
        $this->markTestSkipped("preview");
        $previewApi = CheckoutSdk::builder()
            ->oAuth()
            ->clientCredentials(getenv("CHECKOUT_DEFAULT_PREVIEW_OAUTH_CLIENT_ID"), getenv("CHECKOUT_DEFAULT_PREVIEW_OAUTH_CLIENT_SECRET"))
            ->environment(Environment::sandbox())
            ->build();

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
            }
        );

        $this->assertResponse(
            $paymentResponse,
            "id",
            "reference",
            "status",
            "_links",
            "customer",
            "customer.id",
            "customer.name",
            "customer.email",
            "customer.phone"
        );
    }

    /**
     * @test
     */
    public function shouldMakePayPalPayment()
    {
        $this->markTestSkipped("beta");
        $requestSource = new RequestPayPalSource();

        $product = new Product();
        $product->name = "laptop";
        $product->unit_price = 1000;
        $product->quantity = 1;

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestSource;
        $paymentRequest->capture = true;
        $paymentRequest->amount = 1000;
        $paymentRequest->currency = Currency::$EUR;
        $paymentRequest->items = array($product);
        $paymentRequest->success_url = "https://testing.checkout.com/sucess";
        $paymentRequest->failure_url = "https://testing.checkout.com/failure";

        $paymentResponse1 = $this->retriable(
            function () use (&$paymentRequest) {
                return $this->checkoutApi->getPaymentsClient()->requestPayment($paymentRequest);
            }
        );

        $this->assertResponse(
            $paymentResponse1,
            "id",
            "status",
            "_links",
            "_links.self"
        );

        $paymentResponse2 = $this->retriable(
            function () use (&$paymentResponse1) {
                return $this->checkoutApi->getPaymentsClient()->getPaymentDetails($paymentResponse1["id"]);
            }
        );

        $this->assertResponse(
            $paymentResponse2,
            "id",
            "requested_on",
            "source",
            "amount",
            //"balances",
            "currency",
            "payment_type",
            "status"
        );
    }

    /**
     * @test
     */
    public function shouldMakeAfterPayPayment()
    {
        $requestSource = new RequestAfterPaySource();

        $paymentRequest = new PaymentRequest();
        $paymentRequest->reference = $this->randomEmail();
        $paymentRequest->source = $requestSource;
        $paymentRequest->capture = true;
        $paymentRequest->amount = 10;
        $paymentRequest->currency = Currency::$EUR;
        $paymentRequest->success_url = "https://testing.checkout.com/sucess";
        $paymentRequest->failure_url = "https://testing.checkout.com/failure";

        try {
            $this->checkoutApi->getPaymentsClient()->requestPayment($paymentRequest);
        } catch (Exception $ex) {
            self::assertTrue($ex instanceof CheckoutApiException);
        }
    }

    /**
     * @test
     */
    public function shouldMakeBenefitPayment()
    {
        $requestSource = new RequestBenefitSource();

        $paymentRequest = new PaymentRequest();
        $paymentRequest->reference = $this->randomEmail();
        $paymentRequest->source = $requestSource;
        $paymentRequest->capture = true;
        $paymentRequest->amount = 10;
        $paymentRequest->currency = Currency::$EUR;
        $paymentRequest->success_url = "https://testing.checkout.com/sucess";
        $paymentRequest->failure_url = "https://testing.checkout.com/failure";

        try {
            $this->checkoutApi->getPaymentsClient()->requestPayment($paymentRequest);
        } catch (Exception $ex) {
            self::assertTrue($ex instanceof CheckoutApiException);
        }
    }

    /**
     * @test
     */
    public function shouldMakeQPayPayment()
    {
        $requestSource = new RequestQPaySource();

        $paymentRequest = new PaymentRequest();
        $paymentRequest->reference = $this->randomEmail();
        $paymentRequest->source = $requestSource;
        $paymentRequest->capture = true;
        $paymentRequest->amount = 10;
        $paymentRequest->currency = Currency::$EUR;
        $paymentRequest->success_url = "https://testing.checkout.com/sucess";
        $paymentRequest->failure_url = "https://testing.checkout.com/failure";

        try {
            $this->checkoutApi->getPaymentsClient()->requestPayment($paymentRequest);
        } catch (Exception $ex) {
            self::assertTrue($ex instanceof CheckoutApiException);
        }
    }

    /**
     * @test
     */
    public function shouldMakeMbwayPayment()
    {
        $requestSource = new RequestMbwaySource();

        $paymentRequest = new PaymentRequest();
        $paymentRequest->reference = $this->randomEmail();
        $paymentRequest->source = $requestSource;
        $paymentRequest->capture = true;
        $paymentRequest->amount = 10;
        $paymentRequest->currency = Currency::$EUR;
        $paymentRequest->success_url = "https://testing.checkout.com/sucess";
        $paymentRequest->failure_url = "https://testing.checkout.com/failure";

        try {
            $this->checkoutApi->getPaymentsClient()->requestPayment($paymentRequest);
        } catch (Exception $ex) {
            self::assertTrue($ex instanceof CheckoutApiException);
        }
    }

    /**
     * @test
     */
    public function shouldMakeGiropayPayment()
    {
        $requestSource = new RequestGiropaySource();

        $paymentRequest = new PaymentRequest();
        $paymentRequest->reference = $this->randomEmail();
        $paymentRequest->source = $requestSource;
        $paymentRequest->capture = true;
        $paymentRequest->amount = 10;
        $paymentRequest->currency = Currency::$EUR;
        $paymentRequest->success_url = "https://testing.checkout.com/sucess";
        $paymentRequest->failure_url = "https://testing.checkout.com/failure";

        try {
            $this->checkoutApi->getPaymentsClient()->requestPayment($paymentRequest);
        } catch (Exception $ex) {
            self::assertTrue($ex instanceof CheckoutApiException);
        }
    }
}
