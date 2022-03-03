<?php

namespace Checkout\Tests\Tokens\Four;

use Checkout\Common\Address;
use Checkout\Common\Country;
use Checkout\Common\Phone;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;
use Checkout\Tokens\CardTokenRequest;

class TokensIntegrationTest extends SandboxTestFixture
{

    /**
     * @before
     */
    public function before(): void
    {
        $this->init(PlatformType::$four);
    }

    /**
     * @test
     */
    public function shouldCreateCardToken(): void
    {
        $phone = new Phone();
        $phone->country_code = "44";
        $phone->number = "020 222333";

        $address = new Address();
        $address->address_line1 = "CheckoutSdk.com";
        $address->address_line2 = "90 Tottenham Court Road";
        $address->city = "London";
        $address->state = "London";
        $address->zip = "W1T 4TJ";
        $address->country = Country::$GB;

        $cardTokenRequest = new CardTokenRequest();
        $cardTokenRequest->name = "Mr. Test";
        $cardTokenRequest->number = "4242424242424242";
        $cardTokenRequest->expiry_year = 2025;
        $cardTokenRequest->expiry_month = 6;
        $cardTokenRequest->cvv = "100";
        $cardTokenRequest->billing_address = $address;
        $cardTokenRequest->phone = $phone;

        $this->assertResponse($this->fourApi->getTokensClient()->requestCardToken($cardTokenRequest),
            "token",
            "type",
            "expires_on",
            "expiry_month",
            "expiry_year",
            "name",
            "scheme",
            "last4",
            "bin",
            "card_type",
            "card_category",
            "issuer",
            "issuer_country",
            "product_id",
            "product_type"
        );
    }

}
