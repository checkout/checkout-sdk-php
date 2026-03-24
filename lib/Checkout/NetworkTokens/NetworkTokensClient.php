<?php

namespace Checkout\NetworkTokens;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;
use Checkout\NetworkTokens\Requests\ProvisionNetworkTokenRequest;
use Checkout\NetworkTokens\Requests\RequestCryptogramRequest;
use Checkout\NetworkTokens\Requests\DeleteNetworkTokenRequest;

class NetworkTokensClient extends Client
{
    const NETWORK_TOKENS_PATH = "network-tokens";
    const CRYPTOGRAMS_PATH = "cryptograms";
    const DELETE_PATH = "delete";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$oAuth);
    }

    /**
     * Provision a Network Token
     *
     * Provisions a network token synchronously. If the merchant stores their cards with Checkout.com,
     * then source ID can be used to request a network token for the given card. If the merchant does not
     * store their cards with Checkout.com, then card details have to be provided.
     *
     * @param ProvisionNetworkTokenRequest $provisionNetworkTokenRequest (Required)
     * @return array
     * @throws CheckoutApiException
     */
    public function provisionNetworkToken(ProvisionNetworkTokenRequest $provisionNetworkTokenRequest): array
    {
        return $this->apiClient->post(
            self::NETWORK_TOKENS_PATH,
            $provisionNetworkTokenRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * Get Network Token
     *
     * Given network token ID, this endpoint returns network token details: DPAN, expiry date, state,
     * TRID and also card details like last four and expiry date.
     *
     * @param string $networkTokenId (Required) - Unique token ID assigned by Checkout.com for each token
     * @return array
     * @throws CheckoutApiException
     */
    public function getNetworkToken(string $networkTokenId): array
    {
        return $this->apiClient->get(
            $this->buildPath(self::NETWORK_TOKENS_PATH, $networkTokenId),
            $this->sdkAuthorization()
        );
    }

    /**
     * Request a cryptogram
     *
     * Using network token ID as an input, this endpoint returns token cryptogram.
     *
     * @param string $networkTokenId (Required) - Unique token ID assigned by Checkout.com for each token
     * @param RequestCryptogramRequest $requestCryptogramRequest (Required)
     * @return array
     * @throws CheckoutApiException
     */
    public function requestCryptogram(
        string $networkTokenId,
        RequestCryptogramRequest $requestCryptogramRequest
    ): array {
        return $this->apiClient->post(
            $this->buildPath(self::NETWORK_TOKENS_PATH, $networkTokenId, self::CRYPTOGRAMS_PATH),
            $requestCryptogramRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * Permanently deletes a network token
     *
     * This endpoint is for permanently deleting a network token. A network token should be deleted when a
     * payment instrument it is associated with is removed from file or if the security of the token has been compromised.
     *
     * @param string $networkTokenId (Required) - Unique token ID assigned by Checkout.com for each token
     * @param DeleteNetworkTokenRequest $deleteNetworkTokenRequest (Required)
     * @return array
     * @throws CheckoutApiException
     */
    public function deleteNetworkToken(
        string $networkTokenId,
        DeleteNetworkTokenRequest $deleteNetworkTokenRequest
    ): array {
        return $this->apiClient->patch(
            $this->buildPath(self::NETWORK_TOKENS_PATH, $networkTokenId, self::DELETE_PATH),
            $deleteNetworkTokenRequest,
            $this->sdkAuthorization()
        );
    }
}
