<?php

namespace Checkout\Four;

use Checkout\ApiClient;
use Checkout\Apm\Ideal\IdealClient;
use Checkout\CheckoutConfiguration;

class CheckoutApmApi
{
    private $idealClient;

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        $this->idealClient = new IdealClient($apiClient, $configuration);
    }

    /**
     * @return IdealClient
     */
    public function getIdealClient()
    {
        return $this->idealClient;
    }
}
