<?php

namespace Checkout\Tests\Disputes;

use Checkout\CheckoutApiException;
use Checkout\Disputes\DisputeEvidenceRequest;
use Checkout\Disputes\DisputesClient;
use Checkout\Disputes\DisputesQueryFilter;
use Checkout\Files\FileRequest;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class DisputesClientTest extends UnitTestFixture
{
    private DisputesClient $client;

    /**
     * @before
     */
    public function init(): void
    {
        $this->initMocks(PlatformType::$default);
        $this->client = new DisputesClient($this->apiClient, $this->configuration);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldQueryDispute(): void
    {

        $this->apiClient
            ->method("query")
            ->willReturn("foo");

        $response = $this->client->query(new DisputesQueryFilter());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetDisputeDetails(): void
    {

        $this->apiClient
            ->method("get")
            ->willReturn("foo");

        $response = $this->client->getDisputeDetails("dispute_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldAcceptDispute(): void
    {

        $this->apiClient
            ->method("post")
            ->willReturn("foo");

        $response = $this->client->accept("dispute_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldPutEvidence(): void
    {

        $this->apiClient
            ->method("put")
            ->willReturn("foo");

        $response = $this->client->putEvidence("dispute_id", new DisputeEvidenceRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetEvidence(): void
    {

        $this->apiClient
            ->method("get")
            ->willReturn("foo");

        $response = $this->client->getEvidence("dispute_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSubmitEvidence(): void
    {

        $this->apiClient
            ->method("post")
            ->willReturn("foo");

        $response = $this->client->submitEvidence("dispute_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUploadFile(): void
    {
        $fileRequest = new FileRequest();
        $fileRequest->file = getcwd() . "/test/Checkout/Tests/Resources/checkout.jpeg";

        $this->apiClient
            ->method("submitFile")
            ->willReturn("foo");

        $response = $this->client->uploadFile($fileRequest);
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetFileDetails(): void
    {
        $this->apiClient
            ->method("get")
            ->willReturn("foo");

        $response = $this->client->getFileDetails("file_id");
        $this->assertNotNull($response);
    }

}
