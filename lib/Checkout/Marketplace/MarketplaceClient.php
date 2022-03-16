<?php

namespace Checkout\Marketplace;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Files\FilesClient;
use Checkout\Marketplace\Transfer\CreateTransferRequest;

class MarketplaceClient extends FilesClient
{
    private const MARKETPLACE_PATH = "marketplace";
    private const INSTRUMENT_PATH = "instruments";
    private const FILES_PATH = "files";
    private const ENTITIES_PATH = "entities";
    private const TRANSFERS_PATH = "transfers";

    private ApiClient $filesApiClient;
    private ApiClient $transfersApiClient;

    public function __construct(ApiClient             $apiClient,
                                ApiClient             $filesApiClient,
                                ApiClient             $transfersApiClient,
                                CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$secretKeyOrOAuth);
        $this->filesApiClient = $filesApiClient;
        $this->transfersApiClient = $transfersApiClient;
    }

    /**
     * @param OnboardEntityRequest $entityRequest
     * @return mixed
     * @throws CheckoutApiException
     */
    public function createEntity(OnboardEntityRequest $entityRequest)
    {
        return $this->apiClient->post($this->buildPath(self::MARKETPLACE_PATH, self::ENTITIES_PATH), $entityRequest, $this->sdkAuthorization());
    }

    /**
     * @param string $entityId
     * @return mixed
     * @throws CheckoutApiException
     */
    public function getEntity(string $entityId)
    {
        return $this->apiClient->get($this->buildPath(self::MARKETPLACE_PATH, self::ENTITIES_PATH, $entityId), $this->sdkAuthorization());
    }

    /**
     * @param string $entityId
     * @param OnboardEntityRequest $entityRequest
     * @return mixed
     * @throws CheckoutApiException
     */
    public function updateEntity(string $entityId, OnboardEntityRequest $entityRequest)
    {
        return $this->apiClient->put($this->buildPath(self::MARKETPLACE_PATH, self::ENTITIES_PATH, $entityId), $entityRequest, $this->sdkAuthorization());
    }

    /**
     * @param string $entityId
     * @param MarketplacePaymentInstrument $marketplacePaymentInstrument
     * @return mixed
     * @throws CheckoutApiException
     */
    public function createPaymentInstrument(string $entityId, MarketplacePaymentInstrument $marketplacePaymentInstrument)
    {
        return $this->apiClient->post($this->buildPath(self::MARKETPLACE_PATH, self::ENTITIES_PATH, $entityId, self::INSTRUMENT_PATH), $marketplacePaymentInstrument, $this->sdkAuthorization());
    }

    /**
     * @param MarketplaceFileRequest $marketplaceFileRequest
     * @return mixed
     * @throws CheckoutApiException
     */
    public function submitFile(MarketplaceFileRequest $marketplaceFileRequest)
    {
        return $this->filesApiClient->submitFileFilesApi(self::FILES_PATH, $marketplaceFileRequest, $this->sdkAuthorization());
    }

    /**
     * @param CreateTransferRequest $transferRequest
     * @return mixed
     * @throws CheckoutApiException
     */
    public function initiateTransferOfFunds(CreateTransferRequest $transferRequest)
    {
        return $this->transfersApiClient->post(self::TRANSFERS_PATH, $transferRequest, $this->sdkAuthorization());
    }

}
