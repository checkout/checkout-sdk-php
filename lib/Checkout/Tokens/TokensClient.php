<?php

namespace Checkout\Tokens;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;

class TokensClient extends Client
{
    private const TOKENS_PATH = "tokens";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$publicKey);
    }

    /**
     * @param CardTokenRequest $cardTokenRequest
     * @return mixed
     * @throws CheckoutApiException
     */
    public function requestCardToken(CardTokenRequest $cardTokenRequest)
    {
        return $this->apiClient->post(self::TOKENS_PATH, $cardTokenRequest, $this->sdkAuthorization());
    }

    /**
     * @param WalletTokenRequest $walletTokenRequest
     * @return mixed
     * @throws CheckoutApiException
     */
    public function requestWalletToken(WalletTokenRequest $walletTokenRequest)
    {
        return $this->apiClient->post(self::TOKENS_PATH, $walletTokenRequest, $this->sdkAuthorization());
    }

}
