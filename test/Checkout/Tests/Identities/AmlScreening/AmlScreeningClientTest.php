<?php

namespace Checkout\Tests\Identities\AmlScreening;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Identities\AmlScreening\AmlScreeningClient;
use Checkout\Identities\AmlScreening\Requests\AmlScreeningRequest;
use Checkout\Identities\Entities\SearchParameters;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class AmlScreeningClientTest extends UnitTestFixture
{
    /**
     * @var AmlScreeningClient
     */
    private $client;

    /**
     * @before
     * @throws CheckoutAuthorizationException
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    public function init()
    {
        $this->initMocks(PlatformType::$default);
        $this->client = new AmlScreeningClient($this->apiClient, $this->configuration);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateAmlScreening()
    {
        $expectedResponse = $this->buildExpectedAmlScreeningResponse();
        
        $this->apiClient
            ->method("post")
            ->willReturn($expectedResponse);

        $request = $this->buildAmlScreeningRequest();
        $response = $this->client->createAmlScreening($request);

        $this->assertNotNull($response);
        $this->validateAmlScreeningResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetAmlScreening()
    {
        $expectedResponse = $this->buildExpectedAmlScreeningResponse();
        
        $this->apiClient
            ->method("get")
            ->willReturn($expectedResponse);

        $response = $this->client->getAmlScreening("scr_7hr7swleu6guzjqesyxmyodnya");

        $this->assertNotNull($response);
        $this->validateAmlScreeningResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldHandleCreateAmlScreeningWithAllParameters()
    {
        $expectedResponse = $this->buildExpectedAmlScreeningResponse();
        
        $this->apiClient
            ->method("post")
            ->willReturn($expectedResponse);

        $request = $this->buildAmlScreeningRequest();
        $request->monitored = false;
        
        $response = $this->client->createAmlScreening($request);

        $this->assertNotNull($response);
        $this->validateAmlScreeningResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCallCorrectApiEndpointForCreateAmlScreening()
    {
        $expectedResponse = $this->buildExpectedAmlScreeningResponse();
        
        $this->apiClient
            ->expects($this->once())
            ->method("post")
            ->with("aml-verifications")
            ->willReturn($expectedResponse);

        $request = $this->buildAmlScreeningRequest();
        $response = $this->client->createAmlScreening($request);

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCallCorrectApiEndpointForGetAmlScreening()
    {
        $screeningId = "scr_7hr7swleu6guzjqesyxmyodnya";
        $expectedResponse = $this->buildExpectedAmlScreeningResponse();
        
        $this->apiClient
            ->expects($this->once())
            ->method("get")
            ->with("aml-verifications/" . $screeningId)
            ->willReturn($expectedResponse);

        $response = $this->client->getAmlScreening($screeningId);

        $this->assertNotNull($response);
    }

    private function buildAmlScreeningRequest(): AmlScreeningRequest
    {
        $searchParameters = new SearchParameters();
        $searchParameters->configuration_identifier = "config_123456";

        $request = new AmlScreeningRequest();
        $request->applicant_id = "aplt_7hr7swleu6guzjqesyxmyodnya";
        $request->search_parameters = $searchParameters;
        $request->monitored = true;

        return $request;
    }

    private function buildExpectedAmlScreeningResponse(): array
    {
        return [
            "id" => "scr_7hr7swleu6guzjqesyxmyodnya",
            "applicant_id" => "aplt_7hr7swleu6guzjqesyxmyodnya",
            "status" => "created",
            "search_parameters" => [
                "configuration_identifier" => "config_123456"
            ],
            "monitored" => true,
            "created_on" => "2024-03-20T10:30:00Z",
            "modified_on" => "2024-03-20T10:30:00Z"
        ];
    }

    private function validateAmlScreeningResponse(array $response): void
    {
        $this->assertArrayHasKey("id", $response);
        $this->assertArrayHasKey("applicant_id", $response);
        $this->assertArrayHasKey("status", $response);
        $this->assertArrayHasKey("search_parameters", $response);
        $this->assertArrayHasKey("monitored", $response);
        $this->assertArrayHasKey("created_on", $response);
        $this->assertArrayHasKey("modified_on", $response);
        
        $this->assertNotNull($response["id"]);
        $this->assertNotNull($response["applicant_id"]);
        $this->assertNotNull($response["status"]);
        $this->assertNotNull($response["search_parameters"]);
        $this->assertTrue(is_array($response["search_parameters"]));
        $this->assertArrayHasKey("configuration_identifier", $response["search_parameters"]);
        $this->assertNotNull($response["search_parameters"]["configuration_identifier"]);
    }
}
