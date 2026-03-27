<?php

namespace Checkout\Tests\StandaloneAccountUpdater;

use Checkout\CheckoutApiException;
use Checkout\PlatformType;
use Checkout\StandaloneAccountUpdater\StandaloneAccountUpdaterClient;
use Checkout\StandaloneAccountUpdater\Requests\GetUpdatedCardCredentialsRequest;
use Checkout\StandaloneAccountUpdater\Entities\SourceOptions;
use Checkout\StandaloneAccountUpdater\Entities\Card;
use Checkout\StandaloneAccountUpdater\Entities\Instrument;
use Checkout\Tests\UnitTestFixture;

class StandaloneAccountUpdaterClientTest extends UnitTestFixture
{
    /**
     * @var StandaloneAccountUpdaterClient
     */
    private $client;

    /**
     * @before
     */
    public function init()
    {
        $this->initMocks(PlatformType::$default_oauth);
        $this->client = new StandaloneAccountUpdaterClient($this->apiClient, $this->configuration);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetUpdatedCardCredentialsWithCardRequest()
    {
        $expectedResponse = $this->buildExpectedCardUpdatedResponse();
        
        $this->apiClient
            ->method("post")
            ->willReturn($expectedResponse);

        $request = $this->buildCardRequest();
        $response = $this->client->getUpdatedCardCredentials($request);

        $this->assertCardUpdatedResponse($response, $expectedResponse);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetUpdatedCardCredentialsWithInstrumentRequest()
    {
        $expectedResponse = $this->buildExpectedCardUpdatedResponse();
        
        $this->apiClient
            ->method("post")
            ->willReturn($expectedResponse);

        $request = $this->buildInstrumentRequest();
        $response = $this->client->getUpdatedCardCredentials($request);

        $this->assertCardUpdatedResponse($response, $expectedResponse);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetUpdatedCardCredentialsWithFailedUpdateResponse()
    {
        $expectedResponse = $this->buildExpectedFailedUpdateResponse();
        
        $this->apiClient
            ->method("post")
            ->willReturn($expectedResponse);

        $request = $this->buildCardRequest();
        $response = $this->client->getUpdatedCardCredentials($request);

        $this->assertFailedUpdateResponse($response, $expectedResponse);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCallCorrectApiEndpointForGetUpdatedCardCredentials()
    {
        $expectedResponse = $this->buildExpectedCardUpdatedResponse();
        
        $this->apiClient
            ->expects($this->once())
            ->method("post")
            ->with("account-updater/cards")
            ->willReturn($expectedResponse);

        $request = $this->buildCardRequest();
        $response = $this->client->getUpdatedCardCredentials($request);

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldPassCorrectRequestToApiClient()
    {
        $expectedResponse = $this->buildExpectedCardUpdatedResponse();
        $request = $this->buildCardRequest();
        
        $this->apiClient
            ->expects($this->once())
            ->method("post")
            ->with("account-updater/cards", $request)
            ->willReturn($expectedResponse);

        $response = $this->client->getUpdatedCardCredentials($request);

        $this->assertNotNull($response);
    }

    // Request builders
    private function buildCardRequest(): GetUpdatedCardCredentialsRequest
    {
        $card = new Card();
        $card->number = "4242424242424242";
        $card->expiry_month = 12;
        $card->expiry_year = 2025;

        $sourceOptions = new SourceOptions();
        $sourceOptions->card = $card;

        $request = new GetUpdatedCardCredentialsRequest();
        $request->source_options = $sourceOptions;

        return $request;
    }

    private function buildInstrumentRequest(): GetUpdatedCardCredentialsRequest
    {
        $instrument = new Instrument();
        $instrument->id = "ins_v5rgkf3gdtpuzjqesyxmyodnya";

        $sourceOptions = new SourceOptions();
        $sourceOptions->instrument = $instrument;

        $request = new GetUpdatedCardCredentialsRequest();
        $request->source_options = $sourceOptions;

        return $request;
    }

    // Response builders
    private function buildExpectedCardUpdatedResponse(): array
    {
        return [
            "account_update_status" => "CARD_UPDATED",
            "card" => [
                "encrypted_card_number" => "3nCryp73dFPANv4lu3",
                "bin" => "543642",
                "last4" => "4242",
                "expiry_month" => 5,
                "expiry_year" => 2026,
                "fingerprint" => "abc123fingerprint"
            ]
        ];
    }

    private function buildExpectedFailedUpdateResponse(): array
    {
        return [
            "account_update_status" => "UPDATE_FAILED",
            "account_update_failure_code" => "UP_TO_DATE"
        ];
    }

    private function buildExpectedCardClosedResponse(): array
    {
        return [
            "account_update_status" => "CARD_CLOSED"
        ];
    }

    // Validation helpers
    private function assertCardUpdatedResponse(array $actual, array $expected): void
    {
        $this->assertNotNull($actual);
        $this->assertArrayHasKey("account_update_status", $actual);
        $this->assertEquals($expected["account_update_status"], $actual["account_update_status"]);
        
        if (isset($expected["card"])) {
            $this->assertArrayHasKey("card", $actual);
            $this->assertCardDetailsResponse($actual["card"], $expected["card"]);
        }
    }

    private function assertFailedUpdateResponse(array $actual, array $expected): void
    {
        $this->assertNotNull($actual);
        $this->assertArrayHasKey("account_update_status", $actual);
        $this->assertEquals($expected["account_update_status"], $actual["account_update_status"]);
        
        if (isset($expected["account_update_failure_code"])) {
            $this->assertArrayHasKey("account_update_failure_code", $actual);
            $this->assertEquals($expected["account_update_failure_code"], $actual["account_update_failure_code"]);
        }
    }

    private function assertCardDetailsResponse(array $actual, array $expected): void
    {
        $this->assertNotNull($actual);
        
        if (isset($expected["expiry_month"])) {
            $this->assertArrayHasKey("expiry_month", $actual);
            $this->assertEquals($expected["expiry_month"], $actual["expiry_month"]);
        }
        
        if (isset($expected["expiry_year"])) {
            $this->assertArrayHasKey("expiry_year", $actual);
            $this->assertEquals($expected["expiry_year"], $actual["expiry_year"]);
        }
        
        if (isset($expected["bin"])) {
            $this->assertArrayHasKey("bin", $actual);
            $this->assertEquals($expected["bin"], $actual["bin"]);
        }
        
        if (isset($expected["last4"])) {
            $this->assertArrayHasKey("last4", $actual);
            $this->assertEquals($expected["last4"], $actual["last4"]);
        }
        
        if (isset($expected["fingerprint"])) {
            $this->assertArrayHasKey("fingerprint", $actual);
            $this->assertEquals($expected["fingerprint"], $actual["fingerprint"]);
        }
    }
}
