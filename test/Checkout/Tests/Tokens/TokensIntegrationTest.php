<?php

namespace Checkout\Tests\Tokens;

use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;
use Checkout\Tokens\CardTokenRequest;

class TokensIntegrationTest extends SandboxTestFixture
{

    /**
     * @before
     */
    public function before()
    {
        $this->init(PlatformType::$default);
    }

    /**
     * @test
     */
    public function shouldCreateCardToken()
    {
        $cardTokenRequest = new CardTokenRequest();
        $cardTokenRequest->name = "Mr. Test";
        $cardTokenRequest->number = "4242424242424242";
        $cardTokenRequest->expiry_year = 2025;
        $cardTokenRequest->expiry_month = 6;
        $cardTokenRequest->cvv = "100";
        $cardTokenRequest->billing_address = $this->getAddress();
        $cardTokenRequest->phone = $this->getPhone();

        $this->assertResponse($this->defaultApi->getTokensClient()->requestCardToken($cardTokenRequest),
            "token",
            "type",
            "expires_on",
            "billing_address.address_line1",
            "billing_address.address_line2",
            "billing_address.city",
            "billing_address.state",
            "billing_address.zip",
            "billing_address.country",
            "phone.country_code",
            "phone.number",
            "expiry_month",
            "expiry_year",
            "name",
            "last4",
            "bin"
        //"card_type",
        //"card_category",
        //"issuer",
        //"issuer_country",
        //"product_id",
        //"product_type"
        );
    }

}
