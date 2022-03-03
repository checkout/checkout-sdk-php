<?php

namespace Checkout\Tests\Marketplace;

use Checkout\CheckoutApiException;
use Checkout\CheckoutFileException;
use Checkout\Marketplace\MarketplaceClient;
use Checkout\Marketplace\MarketplaceFileRequest;
use Checkout\Marketplace\MarketplacePaymentInstrument;
use Checkout\Marketplace\OnboardEntityRequest;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class MarketplaceClientTest extends UnitTestFixture
{
    private MarketplaceClient $client;

    /**
     * @before
     */
    public function init(): void
    {
        $this->initMocks(PlatformType::$fourOAuth);
        $this->client = new MarketplaceClient($this->apiClient, $this->apiClient, $this->configuration);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateEntity(): void
    {
        $this->apiClient
            ->method("post")
            ->willReturn("response");

        $response = $this->client->CreateEntity(new OnboardEntityRequest());

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetEntity(): void
    {
        $this->apiClient
            ->method("get")
            ->willReturn("response");

        $response = $this->client->GetEntity("entity_id");

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUpdateEntity(): void
    {
        $this->apiClient
            ->method("put")
            ->willReturn("response");

        $response = $this->client->UpdateEntity("entity_id", new OnboardEntityRequest());

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreatePaymentInstrument(): void
    {
        $this->apiClient
            ->method("post")
            ->willReturn("response");

        $response = $this->client->CreatePaymentInstrument("entity_id", new MarketplacePaymentInstrument());

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException|CheckoutFileException
     */
    public function shouldSubmitFile(): void
    {
        $this->apiClient
            ->method("submitFileFilesApi")
            ->willReturn("response");

        $fileRequest = new MarketplaceFileRequest();
        $fileRequest->file = "filepath";
        $fileRequest->purpose = "individual";
        $fileRequest->content_type = "image/jpeg";

        $response = $this->client->submitFile($fileRequest);

        $this->assertNotNull($response);
    }
}
