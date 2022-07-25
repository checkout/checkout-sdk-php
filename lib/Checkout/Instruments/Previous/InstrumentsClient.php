<?php

namespace Checkout\Instruments\Previous;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;

class InstrumentsClient extends Client
{
    const INSTRUMENTS_PATH = "instruments";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$secretKey);
    }

    /**
     * @param CreateInstrumentRequest $createInstrumentRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function create(CreateInstrumentRequest $createInstrumentRequest)
    {
        return $this->apiClient->post(self::INSTRUMENTS_PATH, $createInstrumentRequest, $this->sdkAuthorization());
    }

    /**
     * @param $instrumentId
     * @return array
     * @throws CheckoutApiException
     */
    public function get($instrumentId)
    {
        return $this->apiClient->get($this->buildPath(self::INSTRUMENTS_PATH, $instrumentId), $this->sdkAuthorization());
    }

    /**
     * @param $instrumentId
     * @param UpdateInstrumentRequest $updateInstrumentRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function update($instrumentId, UpdateInstrumentRequest $updateInstrumentRequest)
    {
        return $this->apiClient->patch($this->buildPath(self::INSTRUMENTS_PATH, $instrumentId), $updateInstrumentRequest, $this->sdkAuthorization());
    }

    /**
     * @param $instrumentId
     * @return array
     * @throws CheckoutApiException
     */
    public function delete($instrumentId)
    {
        return $this->apiClient->delete($this->buildPath(self::INSTRUMENTS_PATH, $instrumentId), $this->sdkAuthorization());
    }

}
