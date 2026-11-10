<?php

namespace Checkout\Reports;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;

class ReportsClient extends Client
{
    const REPORTS_PATH = "reports";
    const FILES_PATH = "files";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$secretKeyOrOAuth);
    }

    /**
     * @param ReportsQuery $filter
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutArgumentException
     */
    public function getAllReports(ReportsQuery $filter)
    {
        return $this->apiClient->query(self::REPORTS_PATH, $filter, $this->sdkAuthorization());
    }

    /**
     * @param $reportId
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutArgumentException
     */
    public function getReportDetails($reportId)
    {
        return $this->apiClient->get($this->buildPath(
            self::REPORTS_PATH,
            $this->encodePathSegment($reportId)
        ), $this->sdkAuthorization());
    }

    /**
     * @param $reportId
     * @param $fileId
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutArgumentException
     */
    public function getReportFile($reportId, $fileId)
    {
        return $this->apiClient->get($this->buildPath(
            self::REPORTS_PATH,
            $this->encodePathSegment($reportId),
            self::FILES_PATH,
            $this->encodePathSegment($fileId)
        ), $this->sdkAuthorization());
    }
}
