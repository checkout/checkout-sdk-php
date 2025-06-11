<?php

namespace Checkout\Tests\Disputes;

use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Disputes\DisputeEvidenceRequest;
use Checkout\Disputes\DisputesClient;
use Checkout\Disputes\DisputesQueryFilter;
use Checkout\Files\FileRequest;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class DisputesClientTest extends UnitTestFixture
{
    /**
     * @var DisputesClient
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
        $this->initMocks(PlatformType::$previous);
        $this->client = new DisputesClient($this->apiClient, $this->configuration, AuthorizationType::$secretKey);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldQueryDispute()
    {

        $this->apiClient
            ->method("query")
            ->willReturn(["foo"]);

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
            ->willReturn(["foo"]);

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
            ->willReturn(["foo"]);

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
            ->willReturn(["foo"]);

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
            ->willReturn(["foo"]);

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
            ->willReturn(["foo"]);

        $response = $this->client->submitEvidence("dispute_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetCompiledSubmittedEvidence()
    {
        $this->apiClient
            ->method("get")
            ->willReturn(["foo"]);

        $response = $this->client->getCompiledSubmittedEvidence("dispute_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUploadFile()
    {
        $fileRequest = new FileRequest();
        $fileRequest->file = self::getCheckoutFilePath();

        $this->apiClient
            ->method("submitFile")
            ->willReturn(["foo"]);

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
            ->willReturn(["foo"]);

        $response = $this->client->getFileDetails("file_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetDisputeSchemeFiles()
    {
        $this->apiClient
            ->method("get")
            ->willReturn(["foo"]);

        $response = $this->client->getDisputeSchemeFiles("dispute_id");
        $this->assertNotNull($response);
    }

}
