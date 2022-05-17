<?php

namespace Checkout\Tests\Payments\Four;

use Checkout\CheckoutApiException;
use Checkout\Common\Country;
use Checkout\Common\Currency;
use Checkout\Common\Four\AccountHolder;
use Checkout\Payments\Four\Request\PaymentRequest;
use Checkout\Payments\Four\Request\Source\RequestBankAccountSource;
use Checkout\Payments\Four\Request\Source\RequestCardSource;
use Checkout\Tests\TestCardSource;

class RequestPaymentsIntegrationTest extends AbstractPaymentsIntegrationTest
{
    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldMakeCardPayment()
    {
        $paymentResponse = $this->makeCardPayment();
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
            "source.id",
            "source.avs_check",
            "source.cvv_check",
            "source.bin",
            "source.card_category",
            "source.card_type",
            "source.expiry_month",
            "source.expiry_year",
            "source.last4",
            "source.scheme",
            "source.name",
            "source.fingerprint",
            //"source.issuer",
            "source.issuer_country",
            "source.product_id",
            "source.product_type",
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
            "scheme_id",
            "response_summary",
            "status",
            "amount",
            "approved",
            "auth_code",
            "currency",
            "source.type",
            "source.id",
            "source.avs_check",
            "source.cvv_check",
            "source.bin",
            "source.card_category",
            "source.card_type",
            "source.expiry_month",
            "source.expiry_year",
            "source.last4",
            "source.scheme",
            "source.name",
            "source.fingerprint",
            //"source.issuer",
            "source.issuer_country",
            "source.product_id",
            "source.product_type",
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
            "scheme_id",
            "response_summary",
            "status",
            "amount",
            "approved",
            "auth_code",
            "currency",
            "source.type",
            "source.id",
            "source.avs_check",
            "source.cvv_check",
            "source.bin",
            "source.card_category",
            "source.card_type",
            "source.expiry_month",
            "source.expiry_year",
            "source.last4",
            "source.scheme",
            "source.name",
            "source.fingerprint",
            //"source.issuer",
            "source.issuer_country",
            "source.product_id",
            "source.product_type",
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

        $paymentResponse1 = $this->fourApi->getPaymentsClient()->requestPayment($paymentRequest, $idempotencyKey);
        $this->assertNotNull($paymentResponse1);

        $paymentResponse2 = $this->fourApi->getPaymentsClient()->requestPayment($paymentRequest, $idempotencyKey);
        $this->assertNotNull($paymentResponse2);

        //$this->assertEquals($paymentResponse1["action_id"], $paymentResponse2["action_id"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldMakeBankAccountPayment()
    {
        $this->markTestSkipped("beta");
        $accountHolder = new AccountHolder();
        $accountHolder->type = "individual";
        $accountHolder->first_name = "John";
        $accountHolder->last_name = "Doe";

        $requestBankAccountSource = new RequestBankAccountSource();
        $requestBankAccountSource->payment_method = "ach";
        $requestBankAccountSource->account_type = "savings";
        $requestBankAccountSource->country = Country::$US;
        $requestBankAccountSource->account_number = "1365456745";
        $requestBankAccountSource->bank_code = "011075150";
        $requestBankAccountSource->account_holder = $accountHolder;

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestBankAccountSource;
        $paymentRequest->amount = 10;
        $paymentRequest->currency = Currency::$USD;
        $paymentRequest->processing_channel_id = "pc_5jp2az55l3cuths25t5p3xhwru";
        $paymentRequest->reference = "Bank Account Payment";
        $paymentRequest->success_url = "https://test.checkout.com/success";
        $paymentRequest->failure_url = "https://test.checkout.com/failure";

        $paymentResponse = $this->fourApi->getPaymentsClient()->requestPayment($paymentRequest);

        $this->assertResponse($paymentResponse, "id");
    }
}
