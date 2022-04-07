<?php

namespace Checkout\Tests\Reconciliation;

use Checkout\CheckoutApi;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutDefaultSdk;
use Checkout\Common\QueryFilterDateRange;
use Checkout\Environment;
use Checkout\Reconciliation\ReconciliationQueryPaymentsFilter;
use Checkout\Tests\SandboxTestFixture;
use DateInterval;
use DateTime;
use DateTimeZone;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class ReconciliationIntegrationTest extends SandboxTestFixture
{
    /**
     * @return CheckoutApi
     * @throws CheckoutArgumentException
     */
    private function productionApi()
    {
        $logger = new Logger("checkout-sdk-test-php-prod");
        $logger->pushHandler(new StreamHandler("php://stderr"));
        $logger->pushHandler(new StreamHandler("checkout-sdk-test-php.log"));
        $builder = CheckoutDefaultSdk::staticKeys();
        $builder->setSecretKey(getenv("CHECKOUT_SECRET_KEY_PROD"));
        $builder->setEnvironment(Environment::production());
        $builder->setLogger($logger);
        return $builder->build();
    }

    private static function getQueryFilterDateRange()
    {
        $from = new DateTime();
        $from->setTimezone(new DateTimeZone("America/Mexico_City"));
        $from->sub(new DateInterval("P1M"));

        $dateRange = new QueryFilterDateRange();
        $dateRange->from = $from;
        $dateRange->to = new DateTime(); // UTC, now
        return $dateRange;
    }

    /**
     * @test
     * @throws CheckoutArgumentException
     */
    public function shouldQueryPaymentsReport()
    {
        $this->markTestSkipped("only available in production");
        $filter = new ReconciliationQueryPaymentsFilter();
        $filter->from = self::getQueryFilterDateRange()->from;
        $filter->to = self::getQueryFilterDateRange()->to;

        $response = self::productionApi()->getReconciliationClient()->queryPaymentsReport($filter);
        $this->assertResponse(
            $response,
            'count',
            'data'
        );
    }

    /**
     * @test
     * @throws CheckoutArgumentException
     */
    public function shouldSinglePaymentReport()
    {
        $this->markTestSkipped("only available in production");
        $response = self::productionApi()->getReconciliationClient()->singlePaymentReport("C8DAEF772R0C5F3F598F");
        $this->assertResponse(
            $response,
            'count',
            'data'
        );
    }

    /**
     * @test
     * @throws CheckoutArgumentException
     */
    public function shouldQueryStatementsReport()
    {
        $this->markTestSkipped("only available in production");
        $report = self::productionApi()->getReconciliationClient()->queryStatementsReport(self::getQueryFilterDateRange());
        $this->assertResponse(
            $report,
            'count',
            'data'
        );
    }

    /**
     * @test
     * @throws CheckoutArgumentException
     */
    public function shouldRetrieveCsvPaymentReport()
    {
        $this->markTestSkipped("only available in production");
        $report = self::productionApi()->getReconciliationClient()->retrieveCsvPaymentReport(self::getQueryFilterDateRange());
        self::assertNotNull($report);
    }

    /**
     * @test
     * @throws CheckoutArgumentException
     */
    public function shouldRetrieveCsvSingleStatementReport()
    {
        $this->markTestSkipped("only available in production");
        $report = self::productionApi()->getReconciliationClient()->retrieveCsvSingleStatementReport("C8DAEF772R0C5F3F598F");
        self::assertNotNull($report);
    }

    /**
     * @test
     * @throws CheckoutArgumentException
     */
    public function shouldRretrieveCsvStatementsReport()
    {
        $this->markTestSkipped("only available in production");
        $report = self::productionApi()->getReconciliationClient()->retrieveCsvStatementsReport(self::getQueryFilterDateRange());
        self::assertNotNull($report);
    }
}
