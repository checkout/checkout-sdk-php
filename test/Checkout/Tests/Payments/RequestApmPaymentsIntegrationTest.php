<?php

namespace Checkout\Tests\Payments;

use Checkout\CheckoutApiException;
use Checkout\Common\Country;
use Checkout\Common\Currency;
use Checkout\Payments\PaymentRequest;
use Checkout\Payments\Source\Apm\BalotoPayer;
use Checkout\Payments\Source\Apm\FawryProduct;
use Checkout\Payments\Source\Apm\IntegrationType;
use Checkout\Payments\Source\Apm\Payer;
use Checkout\Payments\Source\Apm\RequestBalotoSource;
use Checkout\Payments\Source\Apm\RequestBoletoSource;
use Checkout\Payments\Source\Apm\RequestFawrySource;
use Checkout\Payments\Source\Apm\RequestGiropaySource;
use Checkout\Payments\Source\Apm\RequestIdealSource;
use Checkout\Payments\Source\Apm\RequestOxxoSource;
use Checkout\Payments\Source\Apm\RequestPagoFacilSource;
use Checkout\Payments\Source\Apm\RequestRapiPagoSource;
use Checkout\Payments\Source\Apm\RequestSofortSource;


class RequestApmPaymentsIntegrationTest extends AbstractPaymentsIntegrationTest
{

    /**
     * @test
     */
    public function shouldMakeBalotoPayment(): void
    {
        $requestSource = new RequestBalotoSource();
        $requestSource->country = Country::$CO;
        $requestSource->description = "simulate Via Baloto Demo Payment";

        $payer = new BalotoPayer();
        $payer->email = "bruce@wayne-enterprises.com";
        $payer->name = "Bruce Wayne";

        $requestSource->payer = $payer;

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestSource;
        $paymentRequest->amount = 100000;
        $paymentRequest->currency = Currency::$COP;

        $paymentResponse1 = $this->retriable(fn() => $this->defaultApi->getPaymentsClient()->requestPayment($paymentRequest));

        self::assertResponse($paymentResponse1, "id");

        $paymentResponse2 = $this->retriable(fn() => $this->defaultApi->getPaymentsClient()->getPaymentDetails($paymentResponse1["id"]));

        self::assertResponse($paymentResponse2,
            "id",
            "source",
            "amount",
            "_links");
    }

    /**
     * @test
     */
    public function shouldMakeBoletoPayment_Redirect(): void
    {
        $requestSource = new RequestBoletoSource();
        $requestSource->country = Country::$BR;
        $requestSource->description = "boleto payment";
        $requestSource->integration_type = IntegrationType::$redirect;

        $payer = new Payer();
        $payer->email = "bruce@wayne-enterprises.com";
        $payer->name = "Bruce Wayne";
        $payer->document = "53033315550";

        $requestSource->payer = $payer;

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestSource;
        $paymentRequest->amount = 100;
        $paymentRequest->currency = Currency::$BRL;

        $paymentResponse1 = $this->retriable(fn() => $this->defaultApi->getPaymentsClient()->requestPayment($paymentRequest));
        self::assertResponse($paymentResponse1, "id");

        $paymentResponse2 = $this->retriable(fn() => $this->defaultApi->getPaymentsClient()->getPaymentDetails($paymentResponse1["id"]));
        self::assertResponse($paymentResponse2,
            "id",
            "source",
            "amount",
            "_links");
    }

    /**
     * @test
     */
    public function shouldMakeBoletoPayment_Direct(): void
    {
        $requestSource = new RequestBoletoSource();
        $requestSource->country = Country::$BR;
        $requestSource->description = "boleto payment";
        $requestSource->integration_type = IntegrationType::$direct;

        $payer = new Payer();
        $payer->email = "bruce@wayne-enterprises.com";
        $payer->name = "Bruce Wayne";
        $payer->document = "53033315550";

        $requestSource->payer = $payer;

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestSource;
        $paymentRequest->amount = 100;
        $paymentRequest->currency = Currency::$BRL;

        $paymentResponse1 = $this->retriable(fn() => $this->defaultApi->getPaymentsClient()->requestPayment($paymentRequest));
        self::assertResponse($paymentResponse1, "id");

        $paymentResponse2 = $this->retriable(fn() => $this->defaultApi->getPaymentsClient()->getPaymentDetails($paymentResponse1["id"]));
        self::assertResponse($paymentResponse2,
            "id",
            "source",
            "amount",
            "_links");
    }

    /**
     * @test
     */
    public function shouldMakeFawryPayment(): void
    {
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

        $paymentResponse1 = $this->retriable(fn() => $this->defaultApi->getPaymentsClient()->requestPayment($paymentRequest));
        self::assertResponse($paymentResponse1, "id");

        $paymentResponse2 = $this->retriable(fn() => $this->defaultApi->getPaymentsClient()->getPaymentDetails($paymentResponse1["id"]));
        self::assertResponse($paymentResponse2,
            "id",
            "source",
            "amount",
            "_links");
    }

    /**
     * @test
     */
    public function shouldMakeGiropayPayment(): void
    {
        $requestSource = new RequestGiropaySource();
        $requestSource->purpose = "CKO Giropay test";

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestSource;
        $paymentRequest->amount = 1000;
        $paymentRequest->currency = Currency::$EUR;
        $paymentRequest->capture = true;

        $paymentResponse1 = $this->retriable(fn() => $this->defaultApi->getPaymentsClient()->requestPayment($paymentRequest));
        self::assertResponse($paymentResponse1, "id");

        $paymentResponse2 = $this->retriable(fn() => $this->defaultApi->getPaymentsClient()->getPaymentDetails($paymentResponse1["id"]));
        self::assertResponse($paymentResponse2,
            "id",
            "source",
            "amount",
            "_links");
    }

    /**
     * @test
     */
    public function shouldMakeIdealPayment(): void
    {
        $requestSource = new RequestIdealSource();
        $requestSource->bic = "INGBNL2A";
        $requestSource->description = "ORD50234E89";
        $requestSource->language = "nl";

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestSource;
        $paymentRequest->amount = 1000;
        $paymentRequest->currency = Currency::$EUR;
        $paymentRequest->capture = true;

        $paymentResponse1 = $this->retriable(fn() => $this->defaultApi->getPaymentsClient()->requestPayment($paymentRequest));
        self::assertResponse($paymentResponse1, "id");

        $paymentResponse2 = $this->retriable(fn() => $this->defaultApi->getPaymentsClient()->getPaymentDetails($paymentResponse1["id"]));
        self::assertResponse($paymentResponse2,
            "id",
            "source",
            "amount",
            "_links");
    }

    /**
     * @test
     */
    public function shouldMakeOxxoPayment(): void
    {
        $requestSource = new RequestOxxoSource();
        $requestSource->country = Country::$MX;
        $requestSource->description = "ORD50234E89";

        $payer = new Payer();
        $payer->email = "bruce@wayne-enterprises.com";
        $payer->name = "Bruce Wayne";

        $requestSource->payer = $payer;

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestSource;
        $paymentRequest->amount = 100000;
        $paymentRequest->currency = Currency::$MXN;
        $paymentRequest->capture = true;

        $paymentResponse1 = $this->retriable(fn() => $this->defaultApi->getPaymentsClient()->requestPayment($paymentRequest));
        self::assertResponse($paymentResponse1, "id");

        $paymentResponse2 = $this->retriable(fn() => $this->defaultApi->getPaymentsClient()->getPaymentDetails($paymentResponse1["id"]));
        self::assertResponse($paymentResponse2,
            "id",
            "source",
            "amount",
            "_links");
    }

    /**
     * @test
     */
    public function shouldMakePagoFacilPayment(): void
    {
        $requestSource = new RequestPagoFacilSource();
        $requestSource->country = Country::$AR;
        $requestSource->description = "simulate Via Pago Facil Demo Payment";

        $payer = new Payer();
        $payer->email = "bruce@wayne-enterprises.com";
        $payer->name = "Bruce Wayne";

        $requestSource->payer = $payer;

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestSource;
        $paymentRequest->amount = 100000;
        $paymentRequest->currency = Currency::$ARS;
        $paymentRequest->capture = true;

        $paymentResponse1 = $this->retriable(fn() => $this->defaultApi->getPaymentsClient()->requestPayment($paymentRequest));
        self::assertResponse($paymentResponse1, "id");

        $paymentResponse2 = $this->retriable(fn() => $this->defaultApi->getPaymentsClient()->getPaymentDetails($paymentResponse1["id"]));
        self::assertResponse($paymentResponse2,
            "id",
            "source",
            "amount",
            "_links");
    }

    /**
     * @test
     */
    public function shouldMakeRapiPagoPayment(): void
    {
        $requestSource = new RequestRapiPagoSource();
        $requestSource->country = Country::$AR;
        $requestSource->description = "simulate Via Rapi Pago Demo Payment";

        $payer = new Payer();
        $payer->email = "bruce@wayne-enterprises.com";
        $payer->name = "Bruce Wayne";

        $requestSource->payer = $payer;

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestSource;
        $paymentRequest->amount = 100000;
        $paymentRequest->currency = Currency::$ARS;
        $paymentRequest->capture = true;

        $paymentResponse1 = $this->retriable(fn() => $this->defaultApi->getPaymentsClient()->requestPayment($paymentRequest));
        self::assertResponse($paymentResponse1, "id");

        $paymentResponse2 = $this->retriable(fn() => $this->defaultApi->getPaymentsClient()->getPaymentDetails($paymentResponse1["id"]));
        self::assertResponse($paymentResponse2,
            "id",
            "source",
            "amount",
            "_links");
    }

    /**
     * @test
     */
    public function shouldMakeSofortPayment(): void
    {
        $this->markTestSkipped("unstable");
        $requestSource = new RequestSofortSource();

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestSource;
        $paymentRequest->amount = 100000;
        $paymentRequest->currency = Currency::$ARS;
        $paymentRequest->capture = true;

        $paymentResponse1 = $this->retriable(fn() => $this->defaultApi->getPaymentsClient()->requestPayment($paymentRequest));
        self::assertResponse($paymentResponse1, "id");

        $paymentResponse2 = $this->retriable(fn() => $this->defaultApi->getPaymentsClient()->getPaymentDetails($paymentResponse1["id"]));
        self::assertResponse($paymentResponse2,
            "id",
            "source",
            "amount",
            "_links");
    }
}
