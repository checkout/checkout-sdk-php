<?php

namespace Checkout\Tests\Payments;

use Checkout\CheckoutApiException;
use Checkout\Common\Currency;
use Checkout\Payments\PaymentRequest;
use Checkout\Payments\Source\RequestCardSource;
use Checkout\Tests\TestCardSource;
use DateTime;

class RequestPaymentsIntegrationTest extends AbstractPaymentsIntegrationTest
{

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldMakeCardPayment()
    {
        $paymentResponse = $this->makeCardPayment(true, 10, new DateTime());

        $this->assertResponse($paymentResponse,
            "id",
            "processed_on",
            "reference",
            "action_id",
            "response_code",
            "scheme_id",
            "response_summary",
            "status",
            "amount",
            "approved",
            "auth_code",
            "currency",
            "source.type",
            //"source.id",
            //"source.avs_check",
            //"source.cvv_check",
            //"source.bin",
            //"source.card_category",
            //"source.card_type",
            "source.expiry_month",
            "source.expiry_year",
            "source.last4",
            //"source.scheme",
            //"source.name",
            //"source.fast_funds",
            "source.fingerprint",
            //"source.issuer",
            //"source.issuer_country",
            //"source.payouts",
            //"source.product_id",
            //"source.product_type",
            "customer",
            "customer.id",
            "customer.name",
            "processing",
            "processing.acquirer_transaction_id",
            "processing.retrieval_reference_number");
        $this->assertEquals("card", $paymentResponse["source"]["type"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldMakeCard3dsPayment()
    {
        $paymentResponse = $this->make3dsCardPayment();
        $this->assertResponse($paymentResponse,
            "id",
            "reference",
            "status",
            "3ds",
            "3ds.enrolled",
            "customer",
            "customer.id",
            "customer.name",
            "customer.email");
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldMakeCard3dsPayment_N3d()
    {
        $paymentResponse = $this->make3dsCardPayment(true);
        $this->assertResponse($paymentResponse,
            "id",
            "processed_on",
            "reference",
            "action_id",
            "response_code",
            //"scheme_id",
            "response_summary",
            "status",
            "amount",
            "approved",
            "auth_code",
            "currency",
            "source.type",
            //"source.id",
            //"source.avs_check",
            //"source.cvv_check",
            //"source.bin",
            //"source.card_category",
            //"source.card_type",
            "source.expiry_month",
            "source.expiry_year",
            "source.last4",
            //"source.scheme",
            //"source.name",
            //"source.fast_funds",
            "source.fingerprint",
            //"source.issuer",
            //"source.issuer_country",
            //"source.payouts",
            //"source.product_id",
            //"source.product_type",
            "customer",
            "customer.id",
            "customer.name",
            "processing",
            "processing.acquirer_transaction_id",
            "processing.retrieval_reference_number");
        $this->assertEquals("card", $paymentResponse["source"]["type"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldTokenPayment()
    {
        $paymentResponse = $this->makeTokenPayment();

        $this->assertResponse($paymentResponse,
            "id",
            "processed_on",
            "reference",
            "action_id",
            "response_code",
            //"scheme_id",
            "response_summary",
            "status",
            "amount",
            "approved",
            "auth_code",
            "currency",
            "source.type",
            //"source.id",
            //"source.avs_check",
            //"source.cvv_check",
            //"source.bin",
            //"source.card_category",
            //"source.card_type",
            "source.expiry_month",
            "source.expiry_year",
            "source.last4",
            //"source.scheme",
            //"source.name",
            //"source.fast_funds",
            "source.fingerprint",
            //"source.issuer",
            //"source.issuer_country",
            //"source.payouts",
            //"source.product_id",
            //"source.product_type",
            "customer",
            "customer.id",
            "processing",
            "processing.acquirer_transaction_id",
            "processing.retrieval_reference_number");

        $this->assertEquals("card", $paymentResponse["source"]["type"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldMakePaymentsIdempotent()
    {
        $requestCardSource = new RequestCardSource();
        $requestCardSource->name = TestCardSource::$VisaName;
        $requestCardSource->number = TestCardSource::$VisaNumber;
        $requestCardSource->expiry_year = TestCardSource::$VisaExpiryYear;
        $requestCardSource->expiry_month = TestCardSource::$VisaExpiryMonth;
        $requestCardSource->cvv = TestCardSource::$VisaCvv;
        $requestCardSource->billing_address = $this->getAddress();
        $requestCardSource->phone = $this->getPhone();

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestCardSource;
        $paymentRequest->capture = false;
        $paymentRequest->reference = uniqid();
        $paymentRequest->amount = 10;
        $paymentRequest->currency = Currency::$GBP;

        $idempotencyKey = $this->idempotencyKey();

        $paymentResponse1 = $this->defaultApi->getPaymentsClient()->requestPayment($paymentRequest, $idempotencyKey);
        $this->assertNotNull($paymentResponse1);

        $paymentResponse2 = $this->defaultApi->getPaymentsClient()->requestPayment($paymentRequest, $idempotencyKey);
        $this->assertNotNull($paymentResponse2);

        // $this->assertEquals($paymentResponse1["action_id"], $paymentResponse2["action_id"]);
    }

}
