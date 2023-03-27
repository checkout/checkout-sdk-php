<?php

namespace Checkout\Tests\Reports;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\PlatformType;
use Checkout\Reports\ReportsClient;
use Checkout\Reports\ReportsQuery;
use Checkout\Tests\UnitTestFixture;

class ReportsClientTest extends UnitTestFixture
{
    /**
     * @var ReportsClient
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
        $this->client = new ReportsClient($this->apiClient, $this->configuration);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetAllReports()
    {
        $this->apiClient
            ->method("query")
            ->willReturn("foo");

        $response = $this->client->getAllReports(new ReportsQuery());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetReportDetails()
    {
        $this->apiClient
            ->method("get")
            ->willReturn("foo");

        $response = $this->client->getReportDetails("report_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetReportFile()
    {
        $this->apiClient
            ->method("get")
            ->willReturn("foo");

        $response = $this->client->getReportFile("report_id", "file_id");
        $this->assertNotNull($response);
    }
}
