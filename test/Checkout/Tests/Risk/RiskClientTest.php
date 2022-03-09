<?php

namespace Checkout\Tests\Risk;

use Checkout\CheckoutApiException;
use Checkout\PlatformType;
use Checkout\Risk\preauthentication\PreAuthenticationAssessmentRequest;
use Checkout\Risk\precapture\PreCaptureAssessmentRequest;
use Checkout\Risk\RiskClient;
use Checkout\Tests\UnitTestFixture;

class RiskClientTest extends UnitTestFixture
{
    private RiskClient $client;

    /**
     * @before
     */
    public function init(): void
    {
        $this->initMocks(PlatformType::$default);
        $this->client = new RiskClient($this->apiClient, $this->configuration);
    }


    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRequestPreAuthenticationRiskScan(): void
    {

        $this->apiClient
            ->method("post")
            ->willReturn("foo");

        $response = $this->client->requestPreAuthenticationRiskScan(new PreAuthenticationAssessmentRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRequestPreCaptureRiskScan(): void
    {

        $this->apiClient
            ->method("post")
            ->willReturn("foo");

        $response = $this->client->requestPreCaptureRiskScan(new PreCaptureAssessmentRequest());
        $this->assertNotNull($response);
    }

}
