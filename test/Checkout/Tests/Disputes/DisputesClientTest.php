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
    private $client;

    /**
     * @before
     */
    public function init()
    {
        $this->initMocks(PlatformType::$default);
        $this->client = new DisputesClient($this->apiClient, $this->configuration);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldQueryDispute()
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
    public function shouldGetDisputeDetails()
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
    public function shouldAcceptDispute()
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
    public function shouldPutEvidence()
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
    public function shouldGetEvidence()
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
    public function shouldSubmitEvidence()
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
    public function shouldUploadFile()
    {
        $fileRequest = new FileRequest();
        $fileRequest->file = str_replace("\\", "/", getcwd() . "/test/Checkout/Tests/Resources/checkout.jpeg");

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
    public function shouldGetFileDetails()
    {
        $this->apiClient
            ->method("get")
            ->willReturn("foo");

        $response = $this->client->getFileDetails("file_id");
        $this->assertNotNull($response);
    }

}
