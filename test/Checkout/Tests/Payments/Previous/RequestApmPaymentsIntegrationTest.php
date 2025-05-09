<?php

namespace Checkout\Tests\Payments\Previous;

use Checkout\Common\AccountHolder;
use Checkout\Common\Country;
use Checkout\Common\Currency;
use Checkout\Payments\PaymentMethodDetails;
use Checkout\Payments\Previous\PaymentRequest;
use Checkout\Payments\Previous\Source\Apm\IntegrationType;
use Checkout\Payments\Previous\Source\Apm\RequestAlipaySource;
use Checkout\Payments\Previous\Source\Apm\RequestBancontactSource;
use Checkout\Payments\Previous\Source\Apm\RequestBenefitPaySource;
use Checkout\Payments\Previous\Source\Apm\RequestBoletoSource;
use Checkout\Payments\Previous\Source\Apm\RequestEpsSource;
use Checkout\Payments\Previous\Source\Apm\RequestFawrySource;
use Checkout\Payments\Request\Source\Apm\RequestGiropaySource;
use Checkout\Payments\Previous\Source\Apm\RequestIdealSource;
use Checkout\Payments\Previous\Source\Apm\RequestKnetSource;
use Checkout\Payments\Previous\Source\Apm\RequestMultiBancoSource;
use Checkout\Payments\Previous\Source\Apm\RequestOxxoSource;
use Checkout\Payments\Previous\Source\Apm\RequestP24Source;
use Checkout\Payments\Previous\Source\Apm\RequestPagoFacilSource;
use Checkout\Payments\Previous\Source\Apm\RequestPayPalSource;
use Checkout\Payments\Previous\Source\Apm\RequestPoliSource;
use Checkout\Payments\Previous\Source\Apm\RequestQPaySource;
use Checkout\Payments\Previous\Source\Apm\RequestRapiPagoSource;
use Checkout\Payments\Previous\Source\Apm\RequestSofortSource;
use Checkout\Payments\Request\Source\Apm\FawryProduct;

class RequestApmPaymentsIntegrationTest extends AbstractPaymentsIntegrationTest
{

    /**
     * @test
     */
    public function shouldMakeAliPayPayment()
    {
        $this->markTestSkipped("not available");
        $requestSource = new RequestAlipaySource();

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestSource;
        $paymentRequest->amount = 100;
        $paymentRequest->currency = Currency::$USD;

        $paymentResponse1 = $this->retriable(
            function () use (&$paymentRequest) {
                return $this->previousApi->getPaymentsClient()->requestPayment($paymentRequest);
            }
        );
        $this->assertResponse($paymentResponse1, "id");

        $paymentResponse2 = $this->retriable(
            function () use (&$paymentResponse1) {
                return $this->previousApi->getPaymentsClient()->getPaymentDetails($paymentResponse1["id"]);
            }
        );
        $this->assertResponse(
            $paymentResponse2,
            "id",
            "source",
            "amount",
            "_links"
        );
    }

    /**
     * @test
     */
    public function shouldMakeBenefitPayPayment()
    {
        $this->markTestSkipped("not available");
        $requestSource = new RequestBenefitPaySource();
        $requestSource->integration_type = "mobile";

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestSource;
        $paymentRequest->amount = 100;
        $paymentRequest->currency = Currency::$USD;

        $paymentResponse1 = $this->retriable(
            function () use (&$paymentRequest) {
                return $this->previousApi->getPaymentsClient()->requestPayment($paymentRequest);
            }
        );
        $this->assertResponse($paymentResponse1, "id");

        $paymentResponse2 = $this->retriable(
            function () use (&$paymentResponse1) {
                return $this->previousApi->getPaymentsClient()->getPaymentDetails($paymentResponse1["id"]);
            }
        );
        $this->assertResponse(
            $paymentResponse2,
            "id",
            "source",
            "amount",
            "_links"
        );
    }

    /**
     * @test
     */
    public function shouldMakeBoletoPaymentRedirect()
    {
        $this->markTestSkipped("not available");
        $requestSource = new RequestBoletoSource();
        $requestSource->country = Country::$BR;
        $requestSource->description = "boleto payment";
        $requestSource->integration_type = IntegrationType::$redirect;
        $requestSource->payer = $this->getPayer();

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestSource;
        $paymentRequest->amount = 100;
        $paymentRequest->currency = Currency::$BRL;

        $paymentResponse1 = $this->retriable(
            function () use (&$paymentRequest) {
                return $this->previousApi->getPaymentsClient()->requestPayment($paymentRequest);
            }
        );
        $this->assertResponse($paymentResponse1, "id");

        $paymentResponse2 = $this->retriable(
            function () use (&$paymentResponse1) {
                return $this->previousApi->getPaymentsClient()->getPaymentDetails($paymentResponse1["id"]);
            }
        );
        $this->assertResponse(
            $paymentResponse2,
            "id",
            "source",
            "amount",
            "_links"
        );
    }

    /**
     * @test
     */
    public function shouldMakeBoletoPaymentDirect()
    {
        $this->markTestSkipped("not available");
        $requestSource = new RequestBoletoSource();
        $requestSource->country = Country::$BR;
        $requestSource->description = "boleto payment";
        $requestSource->integration_type = IntegrationType::$direct;
        $requestSource->payer = $this->getPayer();

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestSource;
        $paymentRequest->amount = 100;
        $paymentRequest->currency = Currency::$BRL;

        $paymentResponse1 = $this->retriable(
            function () use (&$paymentRequest) {
                return $this->previousApi->getPaymentsClient()->requestPayment($paymentRequest);
            }
        );
        $this->assertResponse($paymentResponse1, "id");

        $paymentResponse2 = $this->retriable(
            function () use (&$paymentResponse1) {
                return $this->previousApi->getPaymentsClient()->getPaymentDetails($paymentResponse1["id"]);
            }
        );
        $this->assertResponse(
            $paymentResponse2,
            "id",
            "source",
            "amount",
            "_links"
        );
    }

    /**
     * @test
     */
    public function shouldMakeEpsPayment()
    {
        $this->markTestSkipped("unavailable");
        $requestSource = new RequestEpsSource();
        $requestSource->purpose = "Mens black t-shirt L";

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestSource;
        $paymentRequest->amount = 100;
        $paymentRequest->currency = Currency::$EUR;

        $paymentResponse1 = $this->retriable(
            function () use (&$paymentRequest) {
                return $this->previousApi->getPaymentsClient()->requestPayment($paymentRequest);
            }
        );
        $this->assertResponse($paymentResponse1, "id");

        $paymentResponse2 = $this->retriable(
            function () use (&$paymentResponse1) {
                return $this->previousApi->getPaymentsClient()->getPaymentDetails($paymentResponse1["id"]);
            }
        );
        $this->assertResponse(
            $paymentResponse2,
            "id",
            "source",
            "amount",
            "_links"
        );
    }

    /**
     * @test
     */
    public function shouldMakeFawryPayment()
    {
        $this->markTestSkipped("unavailable");
        $requestSource = new RequestFawrySource();
        $requestSource->description = "Fawry Demo Payment";
        $requestSource->customer_email = "bruce@wayne-enterprises.com";
        $requestSource->customer_mobile = "01058375055";

        $subObj = new FawryProduct();
        $subObj->product_id = "0123456789";
        $subObj->description = "Fawry Demo Product";
        $subObj->price = 1000;
        $subObj->quantity = 1;

        $requestSource->products = array($subObj);

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestSource;
        $paymentRequest->amount = 1000;
        $paymentRequest->currency = Currency::$EGP;

        $paymentResponse1 = $this->retriable(
            function () use (&$paymentRequest) {
                return $this->previousApi->getPaymentsClient()->requestPayment($paymentRequest);
            }
        );

        $paymentResponse2 = $this->retriable(
            function () use (&$paymentResponse1) {
                return $this->previousApi->getPaymentsClient()->getPaymentDetails($paymentResponse1["id"]);
            }
        );

        $this->assertResponse(
            $paymentResponse2,
            "id",
            "source",
            "amount",
            "_links"
        );
    }

    /**
     * @test
     */
    public function shouldMakeGiropayPayment()
    {
        $this->markTestSkipped("unavailable");
        $accountHolder = new AccountHolder();
        $accountHolder->first_name = "Firstname";
        $accountHolder->last_name = "Lastname";

        $source = new RequestGiropaySource();
        $source->account_holder = $accountHolder;

        $requestSource = $source;

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestSource;
        $paymentRequest->amount = 1000;
        $paymentRequest->currency = Currency::$EUR;
        $paymentRequest->capture = true;

        $paymentResponse1 = $this->retriable(
            function () use (&$paymentRequest) {
                return $this->previousApi->getPaymentsClient()->requestPayment($paymentRequest);
            }
        );

        $paymentResponse2 = $this->retriable(
            function () use (&$paymentResponse1) {
                return $this->previousApi->getPaymentsClient()->getPaymentDetails($paymentResponse1["id"]);
            }
        );

        $this->assertResponse(
            $paymentResponse2,
            "id",
            "source",
            "amount",
            "_links"
        );
    }

    /**
     * @test
     */
    public function shouldMakeIdealPayment()
    {
        $this->markTestSkipped("unavailable");
        $requestSource = new RequestIdealSource();
        $requestSource->bic = "INGBNL2A";
        $requestSource->description = "ORD50234E89";
        $requestSource->language = "nl";

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestSource;
        $paymentRequest->amount = 1000;
        $paymentRequest->currency = Currency::$EUR;
        $paymentRequest->capture = true;

        $paymentResponse1 = $this->retriable(
            function () use (&$paymentRequest) {
                return $this->previousApi->getPaymentsClient()->requestPayment($paymentRequest);
            }
        );
        $this->assertResponse($paymentResponse1, "id");

        $paymentResponse2 = $this->retriable(
            function () use (&$paymentResponse1) {
                return $this->previousApi->getPaymentsClient()->getPaymentDetails($paymentResponse1["id"]);
            }
        );
        $this->assertResponse(
            $paymentResponse2,
            "id",
            "source",
            "amount",
            "_links"
        );
    }

    /**
     * @test
     */
    public function shouldMakeOxxoPayment()
    {
        $this->markTestSkipped("not available");
        $requestSource = new RequestOxxoSource();
        $requestSource->country = Country::$MX;
        $requestSource->description = "ORD50234E89";
        $requestSource->payer = $this->getPayer();

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestSource;
        $paymentRequest->amount = 100000;
        $paymentRequest->currency = Currency::$MXN;
        $paymentRequest->capture = true;

        $paymentResponse1 = $this->retriable(
            function () use (&$paymentRequest) {
                return $this->previousApi->getPaymentsClient()->requestPayment($paymentRequest);
            }
        );
        $this->assertResponse($paymentResponse1, "id");

        $paymentResponse2 = $this->retriable(
            function () use (&$paymentResponse1) {
                return $this->previousApi->getPaymentsClient()->getPaymentDetails($paymentResponse1["id"]);
            }
        );

        $this->assertResponse(
            $paymentResponse2,
            "id",
            "source",
            "amount",
            "_links"
        );
    }

    /**
     * @test
     */
    public function shouldMakePagoFacilPayment()
    {
        $this->markTestSkipped("not available");
        $requestSource = new RequestPagoFacilSource();
        $requestSource->country = Country::$AR;
        $requestSource->description = "simulate Via Pago Facil Demo Payment";
        $requestSource->payer = $this->getPayer();

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestSource;
        $paymentRequest->amount = 100000;
        $paymentRequest->currency = Currency::$ARS;
        $paymentRequest->capture = true;

        $paymentResponse1 = $this->retriable(
            function () use (&$paymentRequest) {
                return $this->previousApi->getPaymentsClient()->requestPayment($paymentRequest);
            }
        );
        $this->assertResponse($paymentResponse1, "id");

        $paymentResponse2 = $this->retriable(
            function () use (&$paymentResponse1) {
                return $this->previousApi->getPaymentsClient()->getPaymentDetails($paymentResponse1["id"]);
            }
        );
        $this->assertResponse(
            $paymentResponse2,
            "id",
            "source",
            "amount",
            "_links"
        );
    }

    /**
     * @test
     */
    public function shouldMakeRapiPagoPayment()
    {
        $this->markTestSkipped("not available");
        $requestSource = new RequestRapiPagoSource();
        $requestSource->country = Country::$AR;
        $requestSource->description = "simulate Via Rapi Pago Demo Payment";
        $requestSource->payer = $this->getPayer();

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestSource;
        $paymentRequest->amount = 100000;
        $paymentRequest->currency = Currency::$ARS;
        $paymentRequest->capture = true;

        $paymentResponse1 = $this->retriable(
            function () use (&$paymentRequest) {
                return $this->previousApi->getPaymentsClient()->requestPayment($paymentRequest);
            }
        );
        $this->assertResponse($paymentResponse1, "id");

        $paymentResponse2 = $this->retriable(
            function () use (&$paymentResponse1) {
                return $this->previousApi->getPaymentsClient()->getPaymentDetails($paymentResponse1["id"]);
            }
        );
        $this->assertResponse(
            $paymentResponse2,
            "id",
            "source",
            "amount",
            "_links"
        );
    }

    /**
     * @test
     */
    public function shouldMakeSofortPayment()
    {
        $this->markTestSkipped("unavailable");
        $requestSource = new RequestSofortSource();

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestSource;
        $paymentRequest->amount = 100;
        $paymentRequest->currency = Currency::$EUR;
        $paymentRequest->capture = true;

        $paymentResponse1 = $this->retriable(
            function () use (&$paymentRequest) {
                return $this->previousApi->getPaymentsClient()->requestPayment($paymentRequest);
            }
        );
        $this->assertResponse($paymentResponse1, "id");

        $paymentResponse2 = $this->retriable(
            function () use (&$paymentResponse1) {
                return $this->previousApi->getPaymentsClient()->getPaymentDetails($paymentResponse1["id"]);
            }
        );
        $this->assertResponse(
            $paymentResponse2,
            "id",
            "source",
            "amount",
            "_links"
        );
    }

    /**
     * @test
     */
    public function shouldMakeKnetPayment()
    {
        $this->markTestSkipped("unavailable");
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

        $paymentResponse1 = $this->retriable(
            function () use (&$paymentRequest) {
                return $this->previousApi->getPaymentsClient()->requestPayment($paymentRequest);
            }
        );
        $this->assertResponse($paymentResponse1, "id");

        $paymentResponse2 = $this->retriable(
            function () use (&$paymentResponse1) {
                return $this->previousApi->getPaymentsClient()->getPaymentDetails($paymentResponse1["id"]);
            }
        );
        $this->assertResponse(
            $paymentResponse2,
            "id",
            "source",
            "amount",
            "_links"
        );
    }

    /**
     * @test
     */
    public function shouldMakePrzelewy24Payment()
    {
        $this->markTestSkipped("unavailable");
        $requestSource = new RequestP24Source();
        $requestSource->payment_country = Country::$PL;
        $requestSource->account_holder_name = "Bruce Wayne";
        $requestSource->account_holder_email = "bruce@wayne-enterprises.com";
        $requestSource->billing_descriptor = "P24 Demo Payment";

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestSource;
        $paymentRequest->amount = 100;
        $paymentRequest->currency = Currency::$PLN;
        $paymentRequest->capture = true;

        $paymentResponse1 = $this->retriable(
            function () use (&$paymentRequest) {
                return $this->previousApi->getPaymentsClient()->requestPayment($paymentRequest);
            }
        );
        $this->assertResponse($paymentResponse1, "id");

        $paymentResponse2 = $this->retriable(
            function () use (&$paymentResponse1) {
                return $this->previousApi->getPaymentsClient()->getPaymentDetails($paymentResponse1["id"]);
            }
        );
        $this->assertResponse(
            $paymentResponse2,
            "id",
            "source",
            "amount",
            "_links"
        );
    }

    /**
     * @test
     */
    public function shouldMakePayPalPayment()
    {
        $this->markTestSkipped("unavailable");
        $requestSource = new RequestPayPalSource();
        $requestSource->invoice_number = "CKO00001";
        $requestSource->logo_url = "https://www.example.com/logo.jpg";

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestSource;
        $paymentRequest->amount = 100;
        $paymentRequest->currency = Currency::$EUR;
        $paymentRequest->capture = true;

        $paymentResponse1 = $this->retriable(
            function () use (&$paymentRequest) {
                return $this->previousApi->getPaymentsClient()->requestPayment($paymentRequest);
            }
        );
        $this->assertResponse($paymentResponse1, "id");

        $paymentResponse2 = $this->retriable(
            function () use (&$paymentResponse1) {
                return $this->previousApi->getPaymentsClient()->getPaymentDetails($paymentResponse1["id"]);
            }
        );
        $this->assertResponse(
            $paymentResponse2,
            "id",
            "source",
            "amount",
            "_links"
        );
    }

    /**
     * @test
     */
    public function shouldMakePoliPayment()
    {
        $this->markTestSkipped("unavailable");
        $requestSource = new RequestPoliSource();

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestSource;
        $paymentRequest->amount = 100;
        $paymentRequest->currency = Currency::$AUD;
        $paymentRequest->capture = true;

        $paymentResponse1 = $this->retriable(
            function () use (&$paymentRequest) {
                return $this->previousApi->getPaymentsClient()->requestPayment($paymentRequest);
            }
        );
        $this->assertResponse($paymentResponse1, "id");

        $paymentResponse2 = $this->retriable(
            function () use (&$paymentResponse1) {
                return $this->previousApi->getPaymentsClient()->getPaymentDetails($paymentResponse1["id"]);
            }
        );
        $this->assertResponse(
            $paymentResponse2,
            "id",
            "source",
            "amount",
            "_links"
        );
    }

    /**
     * @test
     */
    public function shouldMakeBancontactPayment()
    {
        $this->markTestSkipped("unavailable");
        $requestSource = new RequestBancontactSource();
        $requestSource->payment_country = Country::$BE;
        $requestSource->account_holder_name = "Bruce Wayne";
        $requestSource->billing_descriptor = "CKO Demo - bancontact";

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestSource;
        $paymentRequest->amount = 100;
        $paymentRequest->currency = Currency::$EUR;
        $paymentRequest->capture = true;

        $paymentResponse1 = $this->retriable(
            function () use (&$paymentRequest) {
                return $this->previousApi->getPaymentsClient()->requestPayment($paymentRequest);
            }
        );
        $this->assertResponse($paymentResponse1, "id");

        $paymentResponse2 = $this->retriable(
            function () use (&$paymentResponse1) {
                return $this->previousApi->getPaymentsClient()->getPaymentDetails($paymentResponse1["id"]);
            }
        );
        $this->assertResponse(
            $paymentResponse2,
            "id",
            "source",
            "amount",
            "_links"
        );
    }

    /**
     * @test
     */
    public function shouldMakeQPayPayment()
    {
        $this->markTestSkipped("unavailable");
        $requestSource = new RequestQPaySource();
        $requestSource->description = "QPay Demo Payment";
        $requestSource->language = "en";
        $requestSource->quantity = "1";
        $requestSource->national_id = "070AYY010BU234M";

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestSource;
        $paymentRequest->amount = 100;
        $paymentRequest->currency = Currency::$QAR;
        $paymentRequest->capture = true;

        $paymentResponse1 = $this->retriable(
            function () use (&$paymentRequest) {
                return $this->previousApi->getPaymentsClient()->requestPayment($paymentRequest);
            }
        );
        $this->assertResponse($paymentResponse1, "id");

        $paymentResponse2 = $this->retriable(
            function () use (&$paymentResponse1) {
                return $this->previousApi->getPaymentsClient()->getPaymentDetails($paymentResponse1["id"]);
            }
        );
        $this->assertResponse(
            $paymentResponse2,
            "id",
            "source",
            "amount",
            "_links"
        );
    }

    /**
     * @test
     */
    public function shouldMakeMultiBancoPayment()
    {
        $this->markTestSkipped("unavailable");
        $this->markTestSkipped("not available");
        $requestSource = new RequestMultiBancoSource();
        $requestSource->payment_country = Country::$PT;
        $requestSource->account_holder_name = "Bruce Wayne";
        $requestSource->billing_descriptor = "CKO Demo - Multibanco";

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestSource;
        $paymentRequest->amount = 100;
        $paymentRequest->currency = Currency::$QAR;
        $paymentRequest->capture = true;

        $paymentResponse1 = $this->retriable(
            function () use (&$paymentRequest) {
                return $this->previousApi->getPaymentsClient()->requestPayment($paymentRequest);
            }
        );
        $this->assertResponse($paymentResponse1, "id");

        $paymentResponse2 = $this->retriable(
            function () use (&$paymentResponse1) {
                return $this->previousApi->getPaymentsClient()->getPaymentDetails($paymentResponse1["id"]);
            }
        );
        $this->assertResponse(
            $paymentResponse2,
            "id",
            "source",
            "amount",
            "_links"
        );
    }
}
