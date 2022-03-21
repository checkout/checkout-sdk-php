<?php

namespace Checkout\Tests\Instruments\Four;

use Checkout\CheckoutApiException;
use Checkout\Common\Four\AccountHolder;
use Checkout\Customers\Four\CustomerRequest;
use Checkout\Instruments\Four\Create\CreateCustomerInstrumentRequest;
use Checkout\Instruments\Four\Create\CreateTokenInstrumentRequest;
use Checkout\Instruments\Four\Update\UpdateCardInstrumentRequest;
use Checkout\Instruments\Four\Update\UpdateCustomerRequest;
use Checkout\Instruments\Four\Update\UpdateTokenInstrumentRequest;
use Checkout\PlatformType;
use Checkout\Tests\Payments\Four\AbstractPaymentsIntegrationTest;

class InstrumentsIntegrationTest extends AbstractPaymentsIntegrationTest
{

    /**
     * @before
     */
    public function before()
    {
        $this->init(PlatformType::$four);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateAndGetInstrument()
    {
        $tokenInstrument = $this->createTokenInstrument();

        $getInstrument = $this->fourApi->getInstrumentsClient()->get($tokenInstrument["id"]);
        $this->assertResponse($getInstrument,
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
            "customer");
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

        $updateResponse = $this->fourApi->getInstrumentsClient()->update($tokenInstrument["id"], $updateTokenInstrumentRequest);

        $this->assertResponse($updateResponse,
            "type",
            "fingerprint");
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

        $updateResponse = $this->fourApi->getInstrumentsClient()->update($tokenInstrument["id"], $updateCardInstrumentRequest);
        $this->assertResponse($updateResponse,
            "type",
            "fingerprint");


        $cardResponse = $this->fourApi->getInstrumentsClient()->get($tokenInstrument["id"]);
        $this->assertResponse($cardResponse,
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

        $this->fourApi->getInstrumentsClient()->delete($tokenInstrument["id"]);

        $this->expectException(CheckoutApiException::class);
        $this->expectExceptionMessage(self::MESSAGE_404);
        $this->fourApi->getInstrumentsClient()->get($tokenInstrument["id"]);

    }

    /**
     * @return mixed
     * @throws CheckoutApiException
     */
    private function createTokenInstrument()
    {
        $customerRequest = new CustomerRequest();
        $customerRequest->email = $this->randomEmail();
        $customerRequest->name = "Instruments";
        $customerRequest->phone = $this->getPhone();

        $customerResponse = $this->fourApi->getCustomersClient()->create($customerRequest);
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

        $response = $this->fourApi->getInstrumentsClient()->create($createTokenInstrumentRequest);
        $this->assertResponse($response,
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
            "customer");


        return $response;
    }
}
