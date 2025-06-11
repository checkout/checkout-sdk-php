<?php

namespace Checkout\Tests\Risk;

use Checkout\CheckoutApiException;
use Checkout\PlatformType;
use Checkout\Risk\PreAuthentication\PreAuthenticationAssessmentRequest;
use Checkout\Risk\PreCapture\PreCaptureAssessmentRequest;
use Checkout\Risk\RiskClient;
use Checkout\Tests\UnitTestFixture;

class RiskClientTest extends UnitTestFixture
{
    /**
     * @var RiskClient
     */
    private $client;

    /**
     * @before
     */
    public function init()
    {
        $this->initMocks(PlatformType::$previous);
        $this->client = new RiskClient($this->apiClient, $this->configuration);
    }


    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRequestPreAuthenticationRiskScan()
    {

        $this->apiClient
            ->method("post")
            ->willReturn(["foo"]);

        $response = $this->client->requestPreAuthenticationRiskScan(new PreAuthenticationAssessmentRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRequestPreCaptureRiskScan()
    {

        $this->apiClient
            ->method("post")
            ->willReturn(["foo"]);

        $response = $this->client->requestPreCaptureRiskScan(new PreCaptureAssessmentRequest());
        $this->assertNotNull($response);
    }

}
