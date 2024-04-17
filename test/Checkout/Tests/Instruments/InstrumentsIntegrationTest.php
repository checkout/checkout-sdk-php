<?php

namespace Checkout\Tests\Instruments;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Common\AccountHolder;
use Checkout\Common\Country;
use Checkout\Common\Currency;
use Checkout\Customers\CustomerRequest;
use Checkout\Instruments\Create\CreateCustomerInstrumentRequest;
use Checkout\Instruments\Create\CreateSepaInstrumentRequest;
use Checkout\Instruments\Create\CreateTokenInstrumentRequest;
use Checkout\Instruments\Create\InstrumentData;
use Checkout\Instruments\Update\UpdateCardInstrumentRequest;
use Checkout\Instruments\Update\UpdateCustomerRequest;
use Checkout\Instruments\Update\UpdateTokenInstrumentRequest;
use Checkout\Payments\PaymentType;
use Checkout\PlatformType;
use Checkout\Tests\Payments\AbstractPaymentsIntegrationTest;

class InstrumentsIntegrationTest extends AbstractPaymentsIntegrationTest
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
    public function shouldCreateSepaInstrument()
    {
        $instrumentData = new InstrumentData();
        $instrumentData->account_number = "FR7630006000011234567890189";
        $instrumentData->country = Country::$FR;
        $instrumentData->currency = Currency::$EUR;
        $instrumentData->payment_type = PaymentType::$recurring;

        $accountHolder = new AccountHolder();
        $accountHolder->first_name = "John";
        $accountHolder->last_name = "Smith";
        $accountHolder->phone = $this->getPhone();
        $accountHolder->billing_address = $this->getAddress();

        $sepaInstrument = new CreateSepaInstrumentRequest();
        $sepaInstrument->instrument_data = $instrumentData;
        $sepaInstrument->account_holder = $accountHolder;

        $response = $this->checkoutApi->getInstrumentsClient()->create($sepaInstrument);
        $this->assertResponse(
            $response,
            "id",
            "type",
            "fingerprint"
        );
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateAndGetInstrument()
    {
        $tokenInstrument = $this->createTokenInstrument();

        $getInstrument = $this->checkoutApi->getInstrumentsClient()->get($tokenInstrument["id"]);
        $this->assertResponse(
            $getInstrument,
            "id",
            "type",
            "fingerprint",
            "expiry_month",
            "expiry_year",
            "scheme",
            "last4",
            "bin",
            "card_type",
            "card_category",
            //"issuer",
            "issuer_country",
            "product_id",
            "product_type",
            "customer"
        );
        $this->assertEquals($tokenInstrument["id"], $getInstrument["id"]);
        $this->assertEquals($tokenInstrument["fingerprint"], $getInstrument["fingerprint"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUpdateTokenInstrument()
    {
        $tokenInstrument = $this->createTokenInstrument();

        $tokenResponse = $this->requestToken();

        $updateTokenInstrumentRequest = new UpdateTokenInstrumentRequest();
        $updateTokenInstrumentRequest->token = $tokenResponse["token"];

        $updateResponse = $this->checkoutApi->getInstrumentsClient()->update($tokenInstrument["id"], $updateTokenInstrumentRequest);
        $this->assertResponse(
            $updateResponse,
            "type",
            "fingerprint",
            "http_metadata"
        );
        self::assertEquals(200, $updateResponse["http_metadata"]->getStatusCode());
        $this->assertEquals($tokenInstrument["fingerprint"], $updateResponse["fingerprint"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUpdateCardInstrument()
    {
        $tokenInstrument = $this->createTokenInstrument();

        $customer = new UpdateCustomerRequest();
        $customer->id = $tokenInstrument["customer"]["id"];
        $customer->default = true;

        $accountHolder = new AccountHolder();
        $accountHolder->first_name = "John";
        $accountHolder->last_name = "New";
        $accountHolder->phone = $this->getPhone();
        $accountHolder->billing_address = $this->getAddress();


        $updateCardInstrumentRequest = new UpdateCardInstrumentRequest();
        $updateCardInstrumentRequest->expiry_month = 12;
        $updateCardInstrumentRequest->expiry_year = 2024;
        $updateCardInstrumentRequest->name = "John New";
        $updateCardInstrumentRequest->customer = $customer;
        $updateCardInstrumentRequest->account_holder = $accountHolder;

        $updateResponse = $this->checkoutApi->getInstrumentsClient()->update($tokenInstrument["id"], $updateCardInstrumentRequest);
        $this->assertResponse(
            $updateResponse,
            "type",
            "fingerprint"
        );
        self::assertArrayHasKey('http_metadata', $updateResponse);
        self::assertEquals(200, $updateResponse["http_metadata"]->getStatusCode());


        $cardResponse = $this->checkoutApi->getInstrumentsClient()->get($tokenInstrument["id"]);
        $this->assertResponse(
            $cardResponse,
            "id",
            "type",
            "fingerprint",
            "expiry_month",
            "expiry_year",
            "card_type",
            "card_category",
            "product_id",
            "product_type",
            "customer"
        );
        $this->assertEquals($updateResponse["fingerprint"], $cardResponse["fingerprint"]);
        $this->assertEquals($tokenInstrument["customer"]["id"], $cardResponse["customer"]["id"]);
    }


    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldDeleteInstrument()
    {
        $tokenInstrument = $this->createTokenInstrument();

        $deleteResponse = $this->checkoutApi->getInstrumentsClient()->delete($tokenInstrument["id"]);
        self::assertArrayHasKey("http_metadata", $deleteResponse);
        self::assertEquals(204, $deleteResponse["http_metadata"]->getStatusCode());
        $this->expectException(CheckoutApiException::class);
        $this->expectExceptionMessage(self::MESSAGE_404);
        $this->checkoutApi->getInstrumentsClient()->get($tokenInstrument["id"]);
    }

    /**
     * @return array
     * @throws CheckoutApiException
     */
    private function createTokenInstrument()
    {
        $customerRequest = new CustomerRequest();
        $customerRequest->email = $this->randomEmail();
        $customerRequest->name = "Instruments";
        $customerRequest->phone = $this->getPhone();

        $customerResponse = $this->checkoutApi->getCustomersClient()->create($customerRequest);
        $this->assertResponse($customerResponse, "id");

        $tokenResponse = $this->requestToken();

        $accountHolder = new AccountHolder();
        $accountHolder->first_name = "John";
        $accountHolder->last_name = "Smith";
        $accountHolder->phone = $this->getPhone();
        $accountHolder->billing_address = $this->getAddress();

        $createCustomerInstrumentRequest = new CreateCustomerInstrumentRequest();
        $createCustomerInstrumentRequest->id = $customerResponse["id"];

        $createTokenInstrumentRequest = new CreateTokenInstrumentRequest();
        $createTokenInstrumentRequest->token = $tokenResponse["token"];
        $createTokenInstrumentRequest->account_holder = $accountHolder;
        $createTokenInstrumentRequest->customer = $createCustomerInstrumentRequest;

        $response = $this->checkoutApi->getInstrumentsClient()->create($createTokenInstrumentRequest);
        $this->assertResponse(
            $response,
            "id",
            "type",
            "fingerprint",
            "expiry_month",
            "expiry_year",
            "scheme",
            "last4",
            "bin",
            "card_type",
            "card_category",
            //"issuer",
            "issuer_country",
            "product_id",
            "product_type",
            "customer"
        );


        return $response;
    }
}
