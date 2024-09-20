<?php

namespace Checkout\Tests\Payments;

use Checkout\Payments\PaymentMethodDetails;
use Closure;
use Exception;
use Checkout\CheckoutSdk;
use Checkout\Environment;
use Checkout\Common\Phone;
use Checkout\Common\Address;
use Checkout\Common\Country;
use Checkout\Common\Currency;
use Checkout\Payments\Product;
use Checkout\CheckoutApiException;
use Checkout\Common\AccountHolder;
use Checkout\Payments\BillingPlan;
use Checkout\Common\CustomerRequest;
use Checkout\Payments\BillingPlanType;
use Checkout\Payments\ProcessingSettings;
use Checkout\Payments\PaymentCustomerRequest;
use Checkout\Payments\Request\PaymentRequest;
use Checkout\Payments\Request\Source\Apm\FawryProduct;
use Checkout\Payments\Request\Source\Apm\RequestEpsSource;
use Checkout\Payments\Request\Source\Apm\RequestP24Source;
use Checkout\Payments\Request\Source\Apm\RequestAlmaSource;
use Checkout\Payments\Request\Source\Apm\RequestKnetSource;
use Checkout\Payments\Request\Source\Apm\RequestQPaySource;
use Checkout\Payments\Request\Source\Apm\RequestSepaSource;
use Checkout\Payments\Request\Source\Apm\RequestFawrySource;
use Checkout\Payments\Request\Source\Apm\RequestIdealSource;
use Checkout\Payments\Request\Source\Apm\RequestMbwaySource;
use Checkout\Payments\Request\Source\Apm\RequestPayPalSource;
use Checkout\Payments\Request\Source\Apm\RequestSofortSource;
use Checkout\Payments\Request\Source\Apm\RequestStcPaySource;
use Checkout\Payments\Request\Source\Apm\RequestTamaraSource;
use Checkout\Payments\Request\Source\Apm\RequestBenefitSource;
use Checkout\Payments\Request\Source\Apm\RequestGiropaySource;
use Checkout\Payments\Request\Source\Apm\RequestTrustlySource;
use Checkout\Payments\Request\Source\Apm\RequestAfterPaySource;
use Checkout\Payments\Request\Source\Apm\RequestIllicadoSource;
use Checkout\Payments\Request\Source\Apm\RequestCvConnectSource;
use Checkout\Payments\Request\Source\Apm\RequestAlipayPlusSource;
use Checkout\Payments\Request\Source\Apm\RequestBancontactSource;
use Checkout\Payments\Request\Source\Apm\RequestMultiBancoSource;
use Checkout\Payments\Request\Source\Apm\RequestPostFinanceSource;
use Checkout\Payments\Request\Source\Contexts\PaymentContextsKlarnaSource;

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
        $this->markTestSkipped("unavailable");
        $requestSource = new RequestIdealSource();
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
        $this->markTestSkipped("unavailable");
        $requestSource = new RequestPayPalSource();

        $plan = new BillingPlan();
        $plan->type = BillingPlanType::$channel_initiated_billing;
        $plan->skip_shipping_address = true;
        $plan->immutable_shipping_address = false;

        $requestSource->plan = $plan;

        $product = new \Checkout\Payments\Product();
        $product->name = "laptop";
        $product->unit_price = 10;
        $product->quantity = 1;

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestSource;
        $paymentRequest->capture = true;
        $paymentRequest->amount = 10;
        $paymentRequest->currency = Currency::$EUR;
        $paymentRequest->success_url = "https://testing.checkout.com/sucess";
        $paymentRequest->failure_url = "https://testing.checkout.com/failure";
        $paymentRequest->items = array($product);

        $this->checkErrorItem(
            $this->requestFunction($paymentRequest),
            self::$payee_not_onboarded
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
        $paymentRequest->currency = Currency::$BHD;
        $paymentRequest->reference = uniqid();
        $paymentRequest->success_url = "https://testing.checkout.com/sucess";
        $paymentRequest->failure_url = "https://testing.checkout.com/failure";

        $this->checkErrorItem(
            $this->requestFunction($paymentRequest),
            self::$payee_not_onboarded
        );
    }

    /**
     * @test
     */
    public function shouldMakeQPayPayment()
    {
        $requestSource = new RequestQPaySource();
        $requestSource->description = "QPay Demo payment";
        $requestSource->language = "en";
        $requestSource->quantity = 1;
        $requestSource->national_id = "070AYY010BU234M";

        $paymentRequest = new PaymentRequest();
        $paymentRequest->reference = $this->randomEmail();
        $paymentRequest->source = $requestSource;
        $paymentRequest->capture = true;
        $paymentRequest->amount = 10;
        $paymentRequest->currency = Currency::$QAR;
        $paymentRequest->success_url = "https://testing.checkout.com/sucess";
        $paymentRequest->failure_url = "https://testing.checkout.com/failure";

        $this->checkErrorItem(
            $this->requestFunction($paymentRequest),
            self::$payee_not_onboarded
        );
    }

    /**
     * @test
     */
    public function shouldMakeMbwayPayment()
    {
        $this->markTestSkipped("processing_channel_id");
        $requestSource = new RequestMbwaySource();

        $paymentRequest = new PaymentRequest();
        $paymentRequest->reference = $this->randomEmail();
        $paymentRequest->source = $requestSource;
        $paymentRequest->capture = true;
        $paymentRequest->amount = 10;
        $paymentRequest->currency = Currency::$EUR;
        $paymentRequest->processing_channel_id = "pc_5jp2az55l3cuths25t5p3xhwru";
        $paymentRequest->success_url = "https://testing.checkout.com/sucess";
        $paymentRequest->failure_url = "https://testing.checkout.com/failure";

        $this->checkErrorItem(
            $this->requestFunction($paymentRequest),
            self::$apm_service_unavailable
        );
    }

    /**
     * @test
     */
    public function shouldMakeEpsPayment()
    {
        $requestSource = new RequestEpsSource();
        $requestSource->purpose = "Mens black t-shirt L";

        $paymentRequest = new PaymentRequest();
        $paymentRequest->reference = $this->randomEmail();
        $paymentRequest->source = $requestSource;
        $paymentRequest->amount = 10;
        $paymentRequest->currency = Currency::$EUR;
        $paymentRequest->success_url = "https://testing.checkout.com/sucess";
        $paymentRequest->failure_url = "https://testing.checkout.com/failure";

        $this->checkErrorItem(
            $this->requestFunction($paymentRequest),
            self::$payee_not_onboarded
        );
    }

    /**
     * @test
     */
    public function shouldMakeIllicadoPayment()
    {
        $address = new Address();
        $address->address_line1 = "Cecilia Chapman";
        $address->address_line2 = "711-2880 Nulla St.";
        $address->city = "Mankato";
        $address->state = "Mississippi";
        $address->zip = "96522";
        $address->country = Country::$SA;

        $requestSource = new RequestIllicadoSource();
        $requestSource->billing_address = $address;

        $paymentRequest = new PaymentRequest();
        $paymentRequest->reference = $this->randomEmail();
        $paymentRequest->source = $requestSource;
        $paymentRequest->amount = 10;
        $paymentRequest->currency = Currency::$EUR;
        $paymentRequest->success_url = "https://testing.checkout.com/sucess";
        $paymentRequest->failure_url = "https://testing.checkout.com/failure";

        $this->checkErrorItem(
            $this->requestFunction($paymentRequest),
            self::$payee_not_onboarded
        );
    }

    /**
     * @test
     */
    public function shouldMakeGiropayPayment()
    {
        $this->markTestSkipped("Until it's fixed in Sandbox");
        $requestSource = new RequestGiropaySource();

        $paymentRequest = new PaymentRequest();
        $paymentRequest->reference = $this->randomEmail();
        $paymentRequest->source = $requestSource;
        $paymentRequest->capture = true;
        $paymentRequest->amount = 10;
        $paymentRequest->currency = Currency::$EUR;
        $paymentRequest->success_url = "https://testing.checkout.com/sucess";
        $paymentRequest->failure_url = "https://testing.checkout.com/failure";

        $this->checkErrorItem(
            $this->requestFunction($paymentRequest),
            self::$payee_not_onboarded
        );
    }

    /**
     * @test
     */
    public function shouldMakePrzelewy24Payment()
    {
        $requestSource = new RequestP24Source();
        $requestSource->payment_country = Country::$PL;
        $requestSource->account_holder_name = "Bruce Wayne";
        $requestSource->account_holder_email = "bruce@wayne-enterprises.com";
        $requestSource->billing_descriptor = "P24 Demo Payment";

        $paymentRequest = new PaymentRequest();
        $paymentRequest->reference = $this->randomEmail();
        $paymentRequest->source = $requestSource;
        $paymentRequest->capture = true;
        $paymentRequest->amount = 10;
        $paymentRequest->currency = Currency::$EUR;
        $paymentRequest->success_url = "https://testing.checkout.com/sucess";
        $paymentRequest->failure_url = "https://testing.checkout.com/failure";

        $this->checkErrorItem(
            $this->requestFunction($paymentRequest),
            self::$payee_not_onboarded
        );
    }

    /**
     * @test
     */
    public function shouldMakeKnetPayment()
    {
        $paymentMethodDetails = new PaymentMethodDetails();
        $paymentMethodDetails->display_name = "name";
        $paymentMethodDetails->type = "type";
        $paymentMethodDetails->network = "card_network";

        $requestSource = new RequestKnetSource();
        $requestSource->language = "en";
        $requestSource->payment_method_details = $paymentMethodDetails;

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestSource;
        $paymentRequest->amount = 100;
        $paymentRequest->currency = Currency::$KWD;
        $paymentRequest->capture = true;
        $paymentRequest->success_url = "https://testing.checkout.com/sucess";
        $paymentRequest->failure_url = "https://testing.checkout.com/failure";

        $this->checkErrorItem(
            $this->requestFunction($paymentRequest),
            self::$payee_not_onboarded
        );
    }

    /**
     * @test
     */
    public function shouldMakeBancontactPayment()
    {
        $requestSource = new RequestBancontactSource();
        $requestSource->payment_country = Country::$BE;
        $requestSource->account_holder_name = "Bruce Wayne";
        $requestSource->billing_descriptor = "CKO Demo - bancontact";

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestSource;
        $paymentRequest->amount = 100;
        $paymentRequest->currency = Currency::$EUR;
        $paymentRequest->capture = true;
        $paymentRequest->success_url = "https://testing.checkout.com/sucess";
        $paymentRequest->failure_url = "https://testing.checkout.com/failure";

        $paymentResponse = $this->checkoutApi->getPaymentsClient()->requestPayment($paymentRequest);

        $this->assertResponse(
            $paymentResponse,
            "id",
            "status"
        );
    }

    /**
     * @test
     */
    public function shouldMakeMultiBancoPayment()
    {
        $requestSource = new RequestMultiBancoSource();
        $requestSource->payment_country = Country::$PT;
        $requestSource->account_holder_name = "Bruce Wayne";
        $requestSource->billing_descriptor = "CKO Demo - bancontact";

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestSource;
        $paymentRequest->amount = 10;
        $paymentRequest->currency = Currency::$EUR;
        $paymentRequest->capture = true;
        $paymentRequest->success_url = "https://testing.checkout.com/sucess";
        $paymentRequest->failure_url = "https://testing.checkout.com/failure";

        $this->checkErrorItem(
            $this->requestFunction($paymentRequest),
            self::$payee_not_onboarded
        );
    }

    /**
     * @test
     */
    public function shouldMakePostFinancePayment()
    {
        $requestSource = new RequestPostFinanceSource();
        $requestSource->payment_country = Country::$CH;
        $requestSource->account_holder_name = "Bruce Wayne";
        $requestSource->billing_descriptor = "CKO Demo - bancontact";

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestSource;
        $paymentRequest->amount = 10;
        $paymentRequest->currency = Currency::$EUR;
        $paymentRequest->capture = true;
        $paymentRequest->success_url = "https://testing.checkout.com/sucess";
        $paymentRequest->failure_url = "https://testing.checkout.com/failure";

        $this->checkErrorItem(
            $this->requestFunction($paymentRequest),
            self::$payee_not_onboarded
        );
    }

    /**
     * @test
     */
    public function shouldMakeStcPayPayment()
    {
        $requestSource = new RequestStcPaySource();

        $customerRequest = new PaymentCustomerRequest();
        $customerRequest->email = $this->randomEmail();
        $customerRequest->name = "Louis Smith";
        $customerRequest->phone = $this->getPhone();

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestSource;
        $paymentRequest->amount = 10;
        $paymentRequest->currency = Currency::$SAR;
        $paymentRequest->capture = true;
        $paymentRequest->reference = uniqid();
        $paymentRequest->success_url = "https://testing.checkout.com/sucess";
        $paymentRequest->failure_url = "https://testing.checkout.com/failure";
        $paymentRequest->customer = $customerRequest;

        $this->checkErrorItem(
            $this->requestFunction($paymentRequest),
            "merchant_data_delegated_authentication_failed"
        );
    }

    /**
     * @test
     */
    public function shouldMakeAlmaPayment()
    {
        $requestSource = new RequestAlmaSource();
        $requestSource->billing_address = $this->getAddress();

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestSource;
        $paymentRequest->amount = 10;
        $paymentRequest->currency = Currency::$EUR;
        $paymentRequest->capture = true;
        $paymentRequest->success_url = "https://testing.checkout.com/sucess";
        $paymentRequest->failure_url = "https://testing.checkout.com/failure";

        $this->checkErrorItem(
            $this->requestFunction($paymentRequest),
            self::$payee_not_onboarded
        );
    }

    /**
     * @test
     */
    public function shouldMakeKlarnaPayment()
    {
        $this->markTestSkipped("unavailable");
        $accountHolder = new AccountHolder();
        $accountHolder->first_name = "John";
        $accountHolder->last_name = "New";
        $accountHolder->phone = $this->getPhone();
        $accountHolder->billing_address = $this->getAddress();

        $requestSource = new PaymentContextsKlarnaSource();
        $requestSource->account_holder = $accountHolder;

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestSource;
        $paymentRequest->amount = 10;
        $paymentRequest->currency = Currency::$EUR;
        $paymentRequest->capture = true;
        $paymentRequest->success_url = "https://testing.checkout.com/sucess";
        $paymentRequest->failure_url = "https://testing.checkout.com/failure";

        $this->checkErrorItem(
            $this->requestFunction($paymentRequest),
            self::$apm_service_unavailable
        );
    }

    /**
     * @test
     */
    public function shouldMakeFawryPayment()
    {
        $fawryProduct = new FawryProduct();
        $fawryProduct->product_id = "3216012345678954987";
        $fawryProduct->quantity = 1;
        $fawryProduct->price = 10;
        $fawryProduct->description = "Fawry Demo Producet";


        $requestSource = new RequestFawrySource();
        $requestSource->description = "Fawry Demo Payment";
        $requestSource->customer_mobile = "01058375055";
        $requestSource->customer_email = "bruce@wayne-enterprises.com";
        $requestSource->products = array($fawryProduct);


        $paymentRequest = new PaymentRequest();
        $paymentRequest->reference = $this->randomEmail();
        $paymentRequest->source = $requestSource;
        $paymentRequest->capture = true;
        $paymentRequest->amount = 10;
        $paymentRequest->currency = Currency::$EGP;
        $paymentRequest->success_url = "https://testing.checkout.com/sucess";
        $paymentRequest->failure_url = "https://testing.checkout.com/failure";

        $this->checkErrorItem(
            $this->requestFunction($paymentRequest),
            self::$payee_not_onboarded
        );
    }

    /**
     * @test
     */
    public function shouldMakeTrustlyPayment()
    {
        $requestSource = new RequestTrustlySource();
        $requestSource->billing_address = $this->getAddress();

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestSource;
        $paymentRequest->amount = 10;
        $paymentRequest->currency = Currency::$EUR;
        $paymentRequest->capture = true;
        $paymentRequest->success_url = "https://testing.checkout.com/sucess";
        $paymentRequest->failure_url = "https://testing.checkout.com/failure";

        $this->checkErrorItem(
            $this->requestFunction($paymentRequest),
            self::$payee_not_onboarded
        );
    }


    /**
     * @test
     */
    public function shouldMakeCvConnectPayment()
    {
        $requestSource = new RequestCvConnectSource();
        $requestSource->billing_address = $this->getAddress();

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestSource;
        $paymentRequest->amount = 10;
        $paymentRequest->currency = Currency::$EUR;
        $paymentRequest->capture = true;
        $paymentRequest->success_url = "https://testing.checkout.com/sucess";
        $paymentRequest->failure_url = "https://testing.checkout.com/failure";

        $this->checkErrorItem(
            $this->requestFunction($paymentRequest),
            self::$payee_not_onboarded
        );
    }

    /**
     * @test
     */
    public function shouldMakeSepaPayment()
    {
        $requestSource = new RequestSepaSource();
        $requestSource->country = Country::$ES;
        $requestSource->currency = Currency::$EUR;
        $requestSource->account_number = "HU93116000060000000012345676";
        $requestSource->bank_code = "37040044";
        $requestSource->mandate_id = "man_12321233211";
        $requestSource->date_of_signature = "2023-01-01";
        $requestSource->account_holder = $this->getAccountHolder();

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestSource;
        $paymentRequest->amount = 10;
        $paymentRequest->currency = Currency::$EUR;
        $paymentRequest->capture = true;
        $paymentRequest->success_url = "https://testing.checkout.com/sucess";
        $paymentRequest->failure_url = "https://testing.checkout.com/failure";

        $this->checkErrorItem(
            $this->requestFunction($paymentRequest),
            self::$apm_service_unavailable
        );
    }

    /**
     * @param PaymentRequest $paymentRequest
     * @return Closure
     */
    public function requestFunction(PaymentRequest $paymentRequest)
    {
        return function () use (&$paymentRequest) {
            return $this->checkoutApi->getPaymentsClient()->requestPayment($paymentRequest);
        };
    }
}
