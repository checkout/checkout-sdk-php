<?php

namespace Checkout\Tests\Transfers;

use Checkout\CheckoutApiException;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;
use Checkout\Transfers\CreateTransferRequest;
use Checkout\Transfers\TransfersClient;

class TransfersClientTest extends UnitTestFixture
{
    /**
     * @var TransfersClient
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
        $this->initMocks(PlatformType::$default_oauth);
        $this->client = new TransfersClient($this->apiClient, $this->configuration);
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
}
