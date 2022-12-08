<?php

namespace Checkout\Metadata;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;
use Checkout\Metadata\Card\CardMetadataRequest;

class MetadataClient extends Client
{
    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$secretKeyOrOAuth);
    }

    /**
     * @param CardMetadataRequest $cardMetadataRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function requestCardMetadata(CardMetadataRequest $cardMetadataRequest)
    {
        return $this->apiClient->post(
            "metadata/card",
            $cardMetadataRequest,
            $this->sdkAuthorization()
        );
    }

}
