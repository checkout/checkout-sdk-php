<?php

namespace Checkout\Apm\Previous\Sepa;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;

class SepaClient extends Client
{
    const APMS_PATH = "apms";
    const  SEPA_MANDATES_PATH = "sepa/mandates";
    const  PPRO_PATH = "ppro";
    const  CANCEL_PATH = "cancel";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$secretKey);
    }

    /**
     * @param $mandateId
     * @return array
     * @throws CheckoutApiException
     */
    public function getMandate($mandateId)
    {
        return $this->apiClient->get($this->buildPath(self::SEPA_MANDATES_PATH, $mandateId), $this->sdkAuthorization());
    }

    /**
     * @param $mandateId
     * @return array
     * @throws CheckoutApiException
     */
    public function cancelMandate($mandateId)
    {
        return $this->apiClient->post(
            $this->buildPath(self::SEPA_MANDATES_PATH, $mandateId, self::CANCEL_PATH),
            null,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param $mandateId
     * @return array
     * @throws CheckoutApiException
     */
    public function getMandateViaPPro($mandateId)
    {
        return $this->apiClient->get(
            $this->buildPath(self::APMS_PATH, self::PPRO_PATH, self::SEPA_MANDATES_PATH, $mandateId),
            $this->sdkAuthorization()
        );
    }

    /**
     * @param $mandateId
     * @return array
     * @throws CheckoutApiException
     */
    public function cancelMandateViaPPro($mandateId)
    {
        return $this->apiClient->post(
            $this->buildPath(self::APMS_PATH, self::PPRO_PATH, self::SEPA_MANDATES_PATH, $mandateId, self::CANCEL_PATH),
            null,
            $this->sdkAuthorization()
        );
    }
}
