<?php

namespace Checkout\Issuing;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;
use Checkout\Issuing\Cardholders\CardholderRequest;
use Checkout\Issuing\Cards\Create\CardRequest;
use Checkout\Issuing\Cards\Credentials\CardCredentialsQuery;
use Checkout\Issuing\Cards\Enrollment\ThreeDSEnrollmentRequest;
use Checkout\Issuing\Cards\Enrollment\UpdateThreeDSEnrollmentRequest;
use Checkout\Issuing\Cards\Revoke\RevokeCardRequest;
use Checkout\Issuing\Cards\Suspend\SuspendCardRequest;

class IssuingClient extends Client
{
    const ISSUING_PATH = "issuing";
    const CARDHOLDERS_PATH = "cardholders";
    const CARDS_PATH = "cards";
    const THREE_DS_PATH = "3ds-enrollment";
    const ACTIVATE_PATH = "activate";
    const CREDENTIALS_PATH = "credentials";
    const REVOKE_PATH = "revoke";
    const SUSPEND_PATH = "suspend";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$secretKeyOrOAuth);
    }

    /**
     * @param CardholderRequest $cardholderRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function createCardholder(CardholderRequest $cardholderRequest)
    {
        return $this->apiClient->post(
            $this->buildPath(self::ISSUING_PATH, self::CARDHOLDERS_PATH),
            $cardholderRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param $cardholderId
     * @return array
     * @throws CheckoutApiException
     */
    public function getCardholder($cardholderId)
    {
        return $this->apiClient->get(
            $this->buildPath(self::ISSUING_PATH, self::CARDHOLDERS_PATH, $cardholderId),
            $this->sdkAuthorization()
        );
    }

    /**
     * @param $cardholderId
     * @return array
     * @throws CheckoutApiException
     */
    public function getCardholderCards($cardholderId)
    {
        return $this->apiClient->get(
            $this->buildPath(self::ISSUING_PATH, self::CARDHOLDERS_PATH, $cardholderId, self::CARDS_PATH),
            $this->sdkAuthorization()
        );
    }

    /**
     * @param CardRequest $cardRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function createCard(CardRequest $cardRequest)
    {
        return $this->apiClient->post(
            $this->buildPath(self::ISSUING_PATH, self::CARDS_PATH),
            $cardRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param $cardId
     * @return array
     * @throws CheckoutApiException
     */
    public function getCardDetails($cardId)
    {
        return $this->apiClient->get(
            $this->buildPath(self::ISSUING_PATH, self::CARDS_PATH, $cardId),
            $this->sdkAuthorization()
        );
    }

    /**
     * @param string $cardId
     * @param ThreeDSEnrollmentRequest $threeDSEnrollmentRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function enrollThreeDS($cardId, ThreeDSEnrollmentRequest $threeDSEnrollmentRequest)
    {
        return $this->apiClient->post(
            $this->buildPath(self::ISSUING_PATH, self::CARDS_PATH, $cardId, self::THREE_DS_PATH),
            $threeDSEnrollmentRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param string $cardId
     * @param UpdateThreeDSEnrollmentRequest $threeDSEnrollmentRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function updateThreeDSEnrollment($cardId, UpdateThreeDSEnrollmentRequest $threeDSEnrollmentRequest)
    {
        return $this->apiClient->patch(
            $this->buildPath(self::ISSUING_PATH, self::CARDS_PATH, $cardId, self::THREE_DS_PATH),
            $threeDSEnrollmentRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param $cardId
     * @return array
     * @throws CheckoutApiException
     */
    public function getCardThreeDSDetails($cardId)
    {
        return $this->apiClient->get(
            $this->buildPath(self::ISSUING_PATH, self::CARDS_PATH, $cardId, self::THREE_DS_PATH),
            $this->sdkAuthorization()
        );
    }

    /**
     * @param string $cardId
     * @return array
     * @throws CheckoutApiException
     */
    public function activateCard($cardId)
    {
        return $this->apiClient->post(
            $this->buildPath(self::ISSUING_PATH, self::CARDS_PATH, $cardId, self::ACTIVATE_PATH),
            null,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param string $cardId
     * @param CardCredentialsQuery $query
     * @return array
     * @throws CheckoutApiException
     */
    public function getCardCredentials($cardId, CardCredentialsQuery $query)
    {
        return $this->apiClient->query(
            $this->buildPath(self::ISSUING_PATH, self::CARDS_PATH, $cardId, self::CREDENTIALS_PATH),
            $query,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param string $cardId
     * @param RevokeCardRequest $revokeCardRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function revokeCard($cardId, RevokeCardRequest $revokeCardRequest)
    {
        return $this->apiClient->post(
            $this->buildPath(self::ISSUING_PATH, self::CARDS_PATH, $cardId, self::REVOKE_PATH),
            $revokeCardRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param string $cardId
     * @param SuspendCardRequest $suspendCardRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function suspendCard($cardId, SuspendCardRequest $suspendCardRequest)
    {
        return $this->apiClient->post(
            $this->buildPath(self::ISSUING_PATH, self::CARDS_PATH, $cardId, self::SUSPEND_PATH),
            $suspendCardRequest,
            $this->sdkAuthorization()
        );
    }
}
