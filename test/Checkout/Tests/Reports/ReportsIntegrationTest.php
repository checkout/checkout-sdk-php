<?php

namespace Checkout\Tests\Reports;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\PlatformType;
use Checkout\Reports\ReportsQuery;
use Checkout\Tests\SandboxTestFixture;
use DateInterval;
use DateTime;
use DateTimeZone;

class ReportsIntegrationTest extends SandboxTestFixture
{
    /**
     * @before
     * @throws CheckoutAuthorizationException
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    public function before()
    {
        $this->init(PlatformType::$default);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetAllReports()
    {
        $this->markTestSkipped("unstable");

        $response = $this->checkoutApi->getReportsClient()->getAllReports($this->getQuery());

        $this->assertResponse(
            $response,
            "count",
            "limit",
            "data",
            "_links"
        );

        if (array_key_exists("data", $response)) {
            $reports = $response["data"];
            foreach ($reports as $report) {
                $this->assertResponse(
                    $report,
                    "id",
                    "created_on",
                    "type",
                    "description",
                    "account",
                    "from",
                    "to"
                );
            }
        }
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetReportsDetails()
    {
        $response = $this->checkoutApi->getReportsClient()->getAllReports($this->getQuery());

        $this->assertResponse(
            $response,
            "count",
            "limit",
            "data",
            "_links"
        );

        if (array_key_exists("data", $response)) {
            $report = $response["data"][0];
            $details = $this->checkoutApi->getReportsClient()->getReportDetails($report["id"]);

            $this->assertResponse(
                $details,
                "id",
                "created_on",
                "type",
                "description",
                "account",
                "from",
                "to"
            );

            $this->assertEquals($report["id"], $details["id"]);
            $this->assertEquals($report["created_on"], $details["created_on"]);
            $this->assertEquals($report["type"], $details["type"]);
            $this->assertEquals($report["description"], $details["description"]);
            $this->assertEquals($report["account"], $details["account"]);
            $this->assertEquals($report["from"], $details["from"]);
            $this->assertEquals($report["to"], $details["to"]);
        }
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetReportsFile()
    {
        $response = $this->checkoutApi->getReportsClient()->getAllReports($this->getQuery());

        $this->assertResponse(
            $response,
            "count",
            "limit",
            "data",
            "_links"
        );

        if (array_key_exists("data", $response)) {
            $report = $response["data"][0];
            $details = $this->checkoutApi->getReportsClient()->getReportDetails($report["id"]);

            $this->assertResponse(
                $details,
                "id",
                "created_on",
                "type",
                "description",
                "account",
                "from",
                "to"
            );

            $this->assertEquals($report["id"], $details["id"]);
            $this->assertEquals($report["created_on"], $details["created_on"]);
            $this->assertEquals($report["type"], $details["type"]);
            $this->assertEquals($report["description"], $details["description"]);
            $this->assertEquals($report["account"], $details["account"]);
            $this->assertEquals($report["from"], $details["from"]);
            $this->assertEquals($report["to"], $details["to"]);

            $fileId = $report["files"][0]["id"];

            $file = $this->checkoutApi->getReportsClient()->getReportFile($report["id"], $fileId);

            $this->assertNotNull($file["contents"]);
        }
    }

    private function getQuery()
    {
        $created_after = new DateTime();
        $created_after->setTimezone(new DateTimeZone("europe/madrid"));
        $created_after->sub(new DateInterval("P7D"));

        $query = new ReportsQuery();
        $query->created_after = $created_after;
        $query->created_before = new DateTime();

        return $query;
    }
}
