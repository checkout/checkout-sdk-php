<?php

namespace Checkout\Tests\Marketplace;

use Checkout\CheckoutApiException;
use Checkout\Common\Currency;
use Checkout\Marketplace\Balances\BalancesQuery;
use Checkout\Marketplace\MarketplaceClient;
use Checkout\Marketplace\MarketplaceFileRequest;
use Checkout\Marketplace\MarketplacePaymentInstrument;
use Checkout\Marketplace\OnboardEntityRequest;
use Checkout\Marketplace\Transfer\CreateTransferRequest;
use Checkout\Marketplace\UpdateScheduleRequest;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class MarketplaceClientTest extends UnitTestFixture
{
    /**
     * @var MarketplaceClient
     */
    private $client;

    /**
     * @before
     */
    public function init()
    {
        $this->initMocks(PlatformType::$fourOAuth);
        $this->client = new MarketplaceClient(
            $this->apiClient,
            $this->apiClient,
            $this->apiClient,
            $this->apiClient,
            $this->configuration
        );
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateEntity()
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
    public function shouldGetEntity()
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
    public function shouldUpdateEntity()
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
    public function shouldCreatePaymentInstrument()
    {
        $this->apiClient
            ->method("post")
            ->willReturn("response");

        $response = $this->client->CreatePaymentInstrument("entity_id", new MarketplacePaymentInstrument());

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSubmitFile()
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

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldInitiateTransferOfFunds()
    {
        $this->apiClient
            ->method("post")
            ->willReturn("response");

        $transferRequest = new CreateTransferRequest();

        $response = $this->client->initiateTransferOfFunds($transferRequest);

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRetrieveATransfer()
    {
        $this->apiClient
            ->method("get")
            ->willReturn("response");


        $response = $this->client->retrieveATransfer("transfer_id");

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRetrieveEntityBalances()
    {
        $this->apiClient
            ->method("query")
            ->willReturn("response");

        $response = $this->client->retrieveEntityBalances("entity_id", new BalancesQuery());

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUpdatePayoutSchedule()
    {
        $this->apiClient
            ->method("put")
            ->willReturn("response");

        $response = $this->client->updatePayoutSchedule("entity_id", Currency::$USD, new UpdateScheduleRequest());

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRetrievePayoutSchedule()
    {
        $this->apiClient
            ->method("get")
            ->willReturn("response");

        $response = $this->client->retrievePayoutSchedule("entity_id");

        $this->assertNotNull($response);
    }
}