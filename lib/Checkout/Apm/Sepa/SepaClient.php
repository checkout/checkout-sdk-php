<?php

namespace Checkout\Apm\Sepa;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;

class SepaClient extends Client
{
    private const  SEPA_MANDATES_PATH = "sepa/mandates";
    private const  PPRO_PATH = "ppro";
    private const  CANCEL_PATH = "cancel";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$secretKey);
    }

    /**
     * @param string $mandateId
     * @return mixed
     * @throws CheckoutApiException
     */
    public function getMandate(string $mandateId)
    {
        return $this->apiClient->get($this->buildPath(self::SEPA_MANDATES_PATH, $mandateId), $this->sdkAuthorization());
    }

    /**
     * @param string $mandateId
     * @return mixed
     * @throws CheckoutApiException
     */
    public function cancelMandate(string $mandateId)
    {
        return $this->apiClient->post($this->buildPath(self::SEPA_MANDATES_PATH, $mandateId, self::CANCEL_PATH),
            null, $this->sdkAuthorization());
    }

    /**
     * @param string $mandateId
     * @return mixed
     * @throws CheckoutApiException
     */
    public function getMandateViaPPro(string $mandateId)
    {
        return $this->apiClient->get($this->buildPath(self::PPRO_PATH, self::SEPA_MANDATES_PATH, $mandateId),
            $this->sdkAuthorization());
    }

    /**
     * @param string $mandateId
     * @return mixed
     * @throws CheckoutApiException
     */
    public function cancelMandateViaPPro(string $mandateId)
    {
        return $this->apiClient->post($this->buildPath(self::PPRO_PATH, self::SEPA_MANDATES_PATH, $mandateId, self::CANCEL_PATH),
            null, $this->sdkAuthorization());
    }
}
