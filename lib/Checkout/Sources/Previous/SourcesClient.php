<?php

namespace Checkout\Sources\Previous;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;

class SourcesClient extends Client
{

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$secretKey);
    }

    /**
     * @param SepaSourceRequest $sepaSourceRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function createSepaSource(SepaSourceRequest $sepaSourceRequest)
    {
        return $this->apiClient->post("sources", $sepaSourceRequest, $this->sdkAuthorization());
    }

}
