<?php

namespace Checkout\Apm\Ideal;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;

class IdealClient extends Client
{

    const IDEAL_EXTERNAL_PATH = "ideal-external";
    const ISSUERS_PATH = "issuers";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$secretKey);
    }

    /**
     * @return array
     * @throws CheckoutApiException
     */
    public function getInfo()
    {
        return $this->apiClient->get(self::IDEAL_EXTERNAL_PATH, $this->sdkAuthorization());
    }

    /**
     * @return array
     * @throws CheckoutApiException
     */
    public function getIssuers()
    {
        return $this->apiClient->get($this->buildPath(self::IDEAL_EXTERNAL_PATH, self::ISSUERS_PATH), $this->sdkAuthorization());
    }
}
