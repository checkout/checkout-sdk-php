<?php

namespace Checkout\Tests\Payments\Previous;

use Checkout\CheckoutApiException;
use Checkout\Common\Currency;
use Checkout\Common\CustomerRequest;
use Checkout\Payments\Aggregator;
use Checkout\Payments\Previous\PaymentRequest;
use Checkout\Payments\ProcessingSettings;
use Checkout\Payments\Previous\Source\RequestCardSource;
use Checkout\Payments\Previous\Source\RequestTokenSource;
use Checkout\Tests\TestCardSource;
use Checkout\Tokens\CardTokenRequest;
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

        $this->assertResponse(
            $paymentResponse,
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
            "processing.retrieval_reference_number"
        );
        $this->assertEquals("card", $paymentResponse["source"]["type"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldMakeCard3dsPayment()
    {
        $paymentResponse = $this->make3dsCardPayment();
        $this->assertResponse(
            $paymentResponse,
            "id",
            "reference",
            "status",
            "3ds",
            "3ds.enrolled",
            "customer",
            "customer.id",
            "customer.name",
            "customer.email"
        );
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldMakeCard3dsPaymentN3d()
    {
        $paymentResponse = $this->make3dsCardPayment(true);
        $this->assertResponse(
            $paymentResponse,
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
            "processing.retrieval_reference_number"
        );
        $this->assertEquals("card", $paymentResponse["source"]["type"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldTokenPayment()
    {
        $paymentResponse = $this->makeTokenPayment();

        $this->assertResponse(
            $paymentResponse,
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
            "processing.retrieval_reference_number"
        );

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

        $paymentResponse1 = $this->previousApi->getPaymentsClient()->requestPayment($paymentRequest, $idempotencyKey);
        $this->assertNotNull($paymentResponse1);

        $paymentResponse2 = $this->previousApi->getPaymentsClient()->requestPayment($paymentRequest, $idempotencyKey);
        $this->assertNotNull($paymentResponse2);

        // $this->assertEquals($paymentResponse1["action_id"], $paymentResponse2["action_id"]);
    }


    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldMakePaymentsWithAggregator()
    {
        $phone = $this->getPhone();
        $billingAddress = $this->getAddress();

        $cardTokenRequest = new CardTokenRequest();
        $cardTokenRequest->name = TestCardSource::$VisaName;
        $cardTokenRequest->number = TestCardSource::$VisaNumber;
        $cardTokenRequest->expiry_year = TestCardSource::$VisaExpiryYear;
        $cardTokenRequest->expiry_month = TestCardSource::$VisaExpiryMonth;
        $cardTokenRequest->cvv = TestCardSource::$VisaCvv;
        $cardTokenRequest->billing_address = $billingAddress;
        $cardTokenRequest->phone = $phone;

        $cardTokenResponse = $this->previousApi->getTokensClient()->requestCardToken($cardTokenRequest);
        $this->assertResponse($cardTokenResponse, "token");

        $requestTokenSource = new RequestTokenSource();
        $requestTokenSource->token = $cardTokenResponse["token"];

        $customerRequest = new CustomerRequest();
        $customerRequest->email = $this->randomEmail();

        $aggregator = new Aggregator();
        $aggregator->sub_merchant_id = "9874587412";
        $aggregator->sub_merchant_name = "Foodics - catering business";
        $aggregator->sub_merchant_legal_name = "Foodics catering service LLC";
        $aggregator->sub_merchant_street = "Kuwait Street 1";
        $aggregator->sub_merchant_city = "Kuwait City";
        $aggregator->sub_merchant_country = "KWT";
        $aggregator->sub_merchant_postal_code = "60000";
        $aggregator->sub_merchant_state = "Kuwait";
        $aggregator->sub_merchant_email = "[foodcs@support.com](mailto:foodcs@support.com)";
        $aggregator->sub_merchant_phone = "[+965412478112](tel:+965412478112)";
        $aggregator->sub_merchant_industry_code = "5411";

        $processing = new ProcessingSettings();
        $processing->aggregator = $aggregator;

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestTokenSource;
        $paymentRequest->capture = true;
        $paymentRequest->reference = uniqid("paymentAggregator");
        $paymentRequest->amount = 100;
        $paymentRequest->currency = Currency::$SAR;
        $paymentRequest->customer = $customerRequest;
        $paymentRequest->processing = $processing;

        $paymentResponse = $this->previousApi->getPaymentsClient()->requestPayment($paymentRequest);
        $this->assertResponse($paymentResponse, "id");
    }
}
