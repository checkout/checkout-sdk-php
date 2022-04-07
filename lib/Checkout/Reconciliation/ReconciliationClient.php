<?php

namespace Checkout\Reconciliation;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutConfiguration;
use Checkout\Client;
use Checkout\Common\QueryFilterDateRange;

class ReconciliationClient extends Client
{
    const REPORTING_PATH = "reporting";
    const PAYMENTS_PATH = "payments";
    const DOWNLOAD_PATH = "download";
    const STATEMENT_PATH = "statements";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$secretKey);
    }

    public function queryPaymentsReport(ReconciliationQueryPaymentsFilter $reconciliationQueryPaymentsFilter)
    {
        return $this->apiClient->query($this->buildPath(self::REPORTING_PATH, self::PAYMENTS_PATH), $reconciliationQueryPaymentsFilter, $this->sdkAuthorization());
    }

    public function singlePaymentReport($paymentId)
    {
        return $this->apiClient->get($this->buildPath(self::REPORTING_PATH, self::PAYMENTS_PATH, $paymentId), $this->sdkAuthorization());
    }

    public function queryStatementsReport(QueryFilterDateRange $dateRange)
    {
        return $this->apiClient->query($this->buildPath(self::REPORTING_PATH, self::STATEMENT_PATH), $dateRange, $this->sdkAuthorization());
    }

    public function retrieveCsvPaymentReport(QueryFilterDateRange $dateRange)
    {
        return $this->apiClient->query($this->buildPath(self::REPORTING_PATH, self::PAYMENTS_PATH, self::DOWNLOAD_PATH), $dateRange, $this->sdkAuthorization());
    }

    public function retrieveCsvSingleStatementReport($statementId)
    {
        return $this->apiClient->get($this->buildPath(self::REPORTING_PATH, self::STATEMENT_PATH, $statementId, self::PAYMENTS_PATH, self::DOWNLOAD_PATH), $this->sdkAuthorization());
    }

    public function retrieveCsvStatementsReport(QueryFilterDateRange $dateRange)
    {
        return $this->apiClient->query($this->buildPath(self::REPORTING_PATH, self::STATEMENT_PATH, self::DOWNLOAD_PATH), $dateRange, $this->sdkAuthorization());
    }
}
