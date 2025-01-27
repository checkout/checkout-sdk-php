<?php

namespace Checkout\Tests\Instruments\Previous;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Common\Address;
use Checkout\Common\Country;
use Checkout\Common\InstrumentType;
use Checkout\Common\Phone;
use Checkout\Instruments\Previous\CreateInstrumentRequest;
use Checkout\Instruments\Previous\InstrumentAccountHolder;
use Checkout\Instruments\Previous\InstrumentCustomerRequest;
use Checkout\Instruments\Previous\UpdateInstrumentRequest;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;
use Checkout\Tokens\CardTokenRequest;

class InstrumentsIntegrationTest extends SandboxTestFixture
{

    /**
     * @before
     * @throws CheckoutAuthorizationException
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    public function before()
    {
        $this->init(PlatformType::$previous);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateAndGetInstrument()
    {
        $this->markTestSkipped("unavailable");
        $instrument = $this->createInstrument();
        $this->assertResponse(
            $instrument,
            "id",
            "type",
            "expiry_month",
            "expiry_year",
            "scheme",
            "last4",
            "bin",
            //"card_type",
            //"card_category",
            //"issuer",
            //"issuer_country",
            //"product_id",
            //"product_type",
            "fingerprint",
            "customer.id",
            "customer.email",
            "customer.name"
        );

        // get
        $this->assertResponse(
            $this->previousApi->getInstrumentsClient()->get($instrument["id"]),
            "id",
            "type",
            "expiry_month",
            "expiry_year",
            //"scheme",
            "last4",
            "bin",
            //"card_type",
            //"card_category",
            //"issuer",
            //"issuer_country",
            //"product_id",
            //"product_type",
            "fingerprint",
            "customer.id",
            "customer.email",
            "customer.name"
        );
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUpdateAndDeleteInstrument()
    {
        $this->markTestSkipped("unavailable");
        $instrument = $this->createInstrument();

        $updateInstrumentRequest = new UpdateInstrumentRequest();
        $updateInstrumentRequest->name = "testing";

        // update
        $updateResponse = $this->previousApi->getInstrumentsClient()->update($instrument["id"], $updateInstrumentRequest);
        $this->assertResponse(
            $updateResponse,
            "type",
            "fingerprint",
            "http_metadata"
        );
        self::assertEquals(200, $updateResponse["http_metadata"]->getStatusCode());

        // delete
        $deleteResponse = $this->previousApi->getInstrumentsClient()->delete($instrument["id"]);
        self::assertArrayHasKey("http_metadata", $deleteResponse);
        self::assertEquals(204, $deleteResponse["http_metadata"]->getStatusCode());
        try {
            $this->previousApi->getInstrumentsClient()->delete($instrument["id"]);
            $this->fail("shouldn't get here!");
        } catch (CheckoutApiException $e) {
            $this->assertEquals(self::MESSAGE_404, $e->getMessage());
        }
    }

    /**
     * @return array
     * @throws CheckoutApiException
     */
    private function createInstrument()
    {
        $address = new Address();
        $address->address_line1 = "123 Street";
        $address->address_line2 = "Hollywood Avenue";
        $address->city = "Los Angeles";
        $address->state = "CA";
        $address->zip = "91001";
        $address->country = Country::$US;

        $phone = new Phone();
        $phone->country_code = "1";
        $phone->number = "999555222";

        $instrumentAccountHolder = new InstrumentAccountHolder();
        $instrumentAccountHolder->billing_address = $address;
        $instrumentAccountHolder->phone = $phone;

        $customer = new InstrumentCustomerRequest();
        $customer->email = "instrumentcustomer@checkout.com";
        $customer->name = "Instrument Customer";
        $customer->phone = $phone;
        $customer->default = true;

        $token = $this->createToken()["token"];

        $createInstrumentRequest = new CreateInstrumentRequest();
        $createInstrumentRequest->type = InstrumentType::$token;
        $createInstrumentRequest->token = $token;
        $createInstrumentRequest->account_holder = $instrumentAccountHolder;
        $createInstrumentRequest->customer = $customer;

        return $this->previousApi->getInstrumentsClient()->create($createInstrumentRequest);
    }

    /**
     * @return array
     * @throws CheckoutApiException
     */
    private function createToken()
    {
        $phone = new Phone();
        $phone->country_code = "44";
        $phone->number = "020 222333";

        $cardTokenRequest = new CardTokenRequest();
        $cardTokenRequest->name = "Mr. Test";
        $cardTokenRequest->number = "4242424242424242";
        $cardTokenRequest->expiry_year = 2025;
        $cardTokenRequest->expiry_month = 6;
        $cardTokenRequest->cvv = "100";
        $cardTokenRequest->billing_address = $this->getAddress();
        $cardTokenRequest->phone = $phone;

        return $this->previousApi->getTokensClient()->requestCardToken($cardTokenRequest);
    }
}
