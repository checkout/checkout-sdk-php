<?php

namespace Checkout\Apm;

use Checkout\ApiClient;
use Checkout\Apm\Ideal\IdealClient;
use Checkout\Apm\Klarna\KlarnaClient;
use Checkout\Apm\Sepa\SepaClient;
use Checkout\CheckoutConfiguration;

class CheckoutApmApi
{

    private IdealClient $idealClient;
    private KlarnaClient $klarnaClient;
    private SepaClient $sepaClient;

    /**
     * @param ApiClient $apiClient
     * @param CheckoutConfiguration $configuration
     */
    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        $this->idealClient = new IdealClient($apiClient, $configuration);
        $this->klarnaClient = new KlarnaClient($apiClient, $configuration);
        $this->sepaClient = new SepaClient($apiClient, $configuration);
    }

    public function getIdealClient(): IdealClient
    {
        return $this->idealClient;
    }

    public function getKlarnaClient(): KlarnaClient
    {
        return $this->klarnaClient;
    }

    public function getSepaClient(): SepaClient
    {
        return $this->sepaClient;
    }

}
