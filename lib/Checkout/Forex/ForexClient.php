<?php

namespace Checkout\Forex;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;

class ForexClient extends Client
{
    const FOREX_PATH = "forex/quotes";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$oAuth);
    }

    /**
     * @param QuoteRequest $quoteRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function requestQuote(QuoteRequest $quoteRequest)
    {
        return $this->apiClient->post(self::FOREX_PATH, $quoteRequest, $this->sdkAuthorization());
    }
}
