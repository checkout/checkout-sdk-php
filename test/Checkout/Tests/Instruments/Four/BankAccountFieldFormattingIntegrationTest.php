<?php

namespace Checkout\Tests\Instruments\Four;

use Checkout\CheckoutApiException;
use Checkout\CheckoutAuthorizationException;
use Checkout\Common\Country;
use Checkout\Common\Currency;
use Checkout\Common\Four\AccountHolderType;
use Checkout\Instruments\Four\Get\BankAccountFieldQuery;
use Checkout\Instruments\Four\Get\PaymentNetwork;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;

class BankAccountFieldFormattingIntegrationTest extends SandboxTestFixture
{

    /**
     * @before
     * @throws CheckoutAuthorizationException
     */
    public function before(): void
    {
        $this->init(PlatformType::$fourOAuth);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldFailGetBankAccountFieldFormattingWhenNoOAuthIsProvided(): void
    {
        $request = new BankAccountFieldQuery();
        $request->account_holder_type = AccountHolderType::$individual;
        $request->payment_network = PaymentNetwork::$local;

        $response = $this->fourApi->getInstrumentsClient()->getBankAccountFieldFormatting(Country::$GB, Currency::$GBP, $request);

        $this->assertResponse($response, "sections");

        foreach ($response["sections"] as $section) {
            self::assertResponse($section, "name", "fields");
            self::assertNotEmpty($section["fields"]);

            foreach ($section["fields"] as $field) {
                $this->assertResponse($field, "id", "display", "type");
            }
        }
    }
}
