<?php

namespace Checkout\Forward;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;
use Checkout\Forward\Requests\ForwardRequest;

class ForwardClient extends Client
{
    const FORWARD_PATH = "forward";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$secretKeyOrOAuth);
    }

    /**
     * Forward an API request
     *
     * Beta
     * Forwards an API request to a third-party endpoint.
     * For example, you can forward payment credentials you've stored in our Vault to a third-party payment processor.
     * @param ForwardRequest $forwardRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function forwardAnApiRequest(ForwardRequest $forwardRequest)
    {
        return $this->apiClient->post(
            $this->buildPath(self::FORWARD_PATH),
            $forwardRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * Get forward request
     *
     * Retrieve the details of a successfully forwarded API request.
     * The details can be retrieved for up to 14 days after the request was initiated.
     * @param $forwardId
     * @return array
     * @throws CheckoutApiException
     */
    public function getForwardRequest($forwardId)
    {
        return $this->apiClient->get(
            $this->buildPath(self::FORWARD_PATH, $forwardId),
            $this->sdkAuthorization()
        );
    }
}
