<?php

namespace Checkout\Forex;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;

class ForexClient extends Client
{
    const FOREX_PATH = "forex";
    const QUOTES_PATH = "quotes";
    const RATES_PATH = "rates";

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
        return $this->apiClient->post(
            $this->buildPath(self::FOREX_PATH, self::QUOTES_PATH),
            $quoteRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param RatesQueryFilter $ratesQueryFilter
     * @return array
     * @throws CheckoutApiException
     */
    public function getRates(RatesQueryFilter $ratesQueryFilter)
    {
        return $this->apiClient->query(
            $this->buildPath(self::FOREX_PATH, self::RATES_PATH),
            $ratesQueryFilter,
            $this->sdkAuthorization()
        );
    }
}
