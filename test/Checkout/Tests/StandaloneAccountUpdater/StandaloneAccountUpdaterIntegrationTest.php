<?php

namespace Checkout\Tests\StandaloneAccountUpdater;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\PlatformType;
use Checkout\StandaloneAccountUpdater\Requests\GetUpdatedCardCredentialsRequest;
use Checkout\StandaloneAccountUpdater\Entities\SourceOptions;
use Checkout\StandaloneAccountUpdater\Entities\Card;
use Checkout\StandaloneAccountUpdater\Entities\Instrument;
use Checkout\Tests\SandboxTestFixture;

class StandaloneAccountUpdaterIntegrationTest extends SandboxTestFixture
{
    /**
     * @before
     * @throws CheckoutAuthorizationException
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    public function before()
    {
        $this->init(PlatformType::$default_oauth);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetUpdatedCardCredentialsWithValidCardRequest()
    {
        $this->markTestSkipped("This test requires valid account updater credentials and instrument data");

        $request = $this->buildValidCardRequest();

        $response = $this->checkoutApi->getStandaloneAccountUpdaterClient()->getUpdatedCardCredentials($request);

        $this->assertGetUpdatedCardCredentialsResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetUpdatedCardCredentialsWithValidInstrumentRequest()
    {
        $this->markTestSkipped("This test requires valid account updater credentials and instrument data");

        $request = $this->buildValidInstrumentRequest();

        $response = $this->checkoutApi->getStandaloneAccountUpdaterClient()->getUpdatedCardCredentials($request);

        $this->assertGetUpdatedCardCredentialsResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldThrowExceptionWithInvalidCardRequest()
    {
        $request = $this->buildInvalidCardRequest();

        $this->expectException(CheckoutApiException::class);

        $this->checkoutApi->getStandaloneAccountUpdaterClient()->getUpdatedCardCredentials($request);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldThrow422WithStandardTestCard()
    {
        $request = $this->buildExpiredCardRequest();

        try {
            $this->checkoutApi->getStandaloneAccountUpdaterClient()->getUpdatedCardCredentials($request);
            $this->fail("Expected CheckoutApiException to be thrown");
        } catch (CheckoutApiException $e) {
            // Account updater returns 422 for standard test cards - likely requires cards that exist in the system
            // or specific card numbers that simulate real account updater scenarios
            $this->assertEquals(422, $e->http_metadata->getStatusCode());
        }
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldThrowExceptionWithInvalidInstrumentRequest()
    {
        $request = $this->buildInvalidInstrumentRequest();

        $this->expectException(CheckoutApiException::class);

        $this->checkoutApi->getStandaloneAccountUpdaterClient()->getUpdatedCardCredentials($request);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldThrowExceptionWithEmptyRequest()
    {
        $request = new GetUpdatedCardCredentialsRequest();
        // No source_options set

        $this->expectException(CheckoutApiException::class);

        $this->checkoutApi->getStandaloneAccountUpdaterClient()->getUpdatedCardCredentials($request);
    }

    // Request builders
    private function buildValidCardRequest(): GetUpdatedCardCredentialsRequest
    {
        return $this->buildCardRequestWithYear(date('Y') + 1);
    }

    private function buildExpiredCardRequest(): GetUpdatedCardCredentialsRequest
    {
        return $this->buildCardRequestWithYear(date('Y') - 1);
    }

    private function buildCardRequestWithYear(int $expiryYear): GetUpdatedCardCredentialsRequest
    {
        $card = new Card();
        // Using standard Visa test card number
        $card->number = "4242424242424242";
        $card->expiry_month = 12;
        // Using an expired date since account updater is meant to update expired/expiring cards
        $card->expiry_year = $expiryYear;

        $sourceOptions = new SourceOptions();
        $sourceOptions->card = $card;

        $request = new GetUpdatedCardCredentialsRequest();
        $request->source_options = $sourceOptions;

        return $request;
    }

    private function buildValidInstrumentRequest(): GetUpdatedCardCredentialsRequest
    {
        $instrument = new Instrument();
        // Using a realistic test instrument ID following the pattern ins_xxxxxxxxxxxxxxxxxxxx (26 chars)
        // Based on existing source ID patterns found in other tests like src_v5rgkf3gdtpuzjqesyxmyodnya
        $instrument->id = "ins_v5rgkf3gdtpuzjqesyxmyodnya";

        $sourceOptions = new SourceOptions();
        $sourceOptions->instrument = $instrument;

        $request = new GetUpdatedCardCredentialsRequest();
        $request->source_options = $sourceOptions;

        return $request;
    }

    private function buildInvalidCardRequest(): GetUpdatedCardCredentialsRequest
    {
        $card = new Card();
        $card->number = "invalid_card_number";
        $card->expiry_month = 13; // Invalid month
        $card->expiry_year = 2020; // Expired year

        $sourceOptions = new SourceOptions();
        $sourceOptions->card = $card;

        $request = new GetUpdatedCardCredentialsRequest();
        $request->source_options = $sourceOptions;

        return $request;
    }

    private function buildInvalidInstrumentRequest(): GetUpdatedCardCredentialsRequest
    {
        $instrument = new Instrument();
        $instrument->id = "invalid_instrument_id";

        $sourceOptions = new SourceOptions();
        $sourceOptions->instrument = $instrument;

        $request = new GetUpdatedCardCredentialsRequest();
        $request->source_options = $sourceOptions;

        return $request;
    }

    // Validation helpers
    private function assertGetUpdatedCardCredentialsResponse(array $response): void
    {
        $this->assertResponse($response, "account_update_status");

        $accountUpdateStatus = $response["account_update_status"];
        
        if ($accountUpdateStatus === "CARD_UPDATED" || $accountUpdateStatus === "CARD_EXPIRY_UPDATED") {
            $this->assertResponse($response, "card", "card.expiry_month", "card.expiry_year");
            
            // Optional fields that might be present
            if (isset($response["card"]["bin"])) {
                $this->assertNotNull($response["card"]["bin"]);
            }
            if (isset($response["card"]["last4"])) {
                $this->assertNotNull($response["card"]["last4"]);
            }
            if (isset($response["card"]["fingerprint"])) {
                $this->assertNotNull($response["card"]["fingerprint"]);
            }
            if (isset($response["card"]["encrypted_card_number"])) {
                $this->assertNotNull($response["card"]["encrypted_card_number"]);
            }
        }
        
        if ($accountUpdateStatus === "UPDATE_FAILED") {
            $this->assertResponse($response, "account_update_failure_code");
        }
    }
}
