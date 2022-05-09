<?php

namespace Checkout\Sessions;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;
use Checkout\Sessions\Channel\ChannelData;

class SessionsClient extends Client
{
    const SESSIONS_PATH = "sessions";
    const COLLECT_DATA_PATH = "collect-data";
    const COMPLETE_PATH = "complete";
    const ISSUER_FINGERPRINT_PATH = "issuer-fingerprint";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$oAuth);
    }

    /**
     * @param SessionRequest $sessionRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function requestSession(SessionRequest $sessionRequest)
    {
        return $this->apiClient->post(self::SESSIONS_PATH, $sessionRequest, $this->sdkAuthorization());
    }

    /**
     * @param $sessionId
     * @param string|null $sessionSecret
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutAuthorizationException
     */
    public function getSessionDetails($sessionId, $sessionSecret = null)
    {
        return $this->apiClient->get($this->buildPath(self::SESSIONS_PATH, $sessionId), $this->getSdkAuthorization($sessionSecret));
    }

    /**
     * @param $sessionId
     * @param ChannelData $channelData
     * @param string|null $sessionSecret
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutAuthorizationException
     */
    public function updateSession($sessionId, ChannelData $channelData, $sessionSecret = null)
    {
        return $this->apiClient->put($this->buildPath(self::SESSIONS_PATH, $sessionId, self::COLLECT_DATA_PATH), $channelData, $this->getSdkAuthorization($sessionSecret));
    }

    /**
     * @param $sessionId
     * @param string|null $sessionSecret
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutAuthorizationException
     */
    public function completeSession($sessionId, $sessionSecret = null)
    {
        return $this->apiClient->post($this->buildPath(self::SESSIONS_PATH, $sessionId, self::COMPLETE_PATH), null, $this->getSdkAuthorization($sessionSecret));
    }

    /**
     * @param $sessionId
     * @param ThreeDsMethodCompletionRequest $threeDsMethodCompletionRequest
     * @param string|null $sessionSecret
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutAuthorizationException
     */
    public function updateThreeDsMethodCompletionIndicator($sessionId, ThreeDsMethodCompletionRequest $threeDsMethodCompletionRequest, $sessionSecret = null)
    {
        return $this->apiClient->put($this->buildPath(self::SESSIONS_PATH, $sessionId, self::ISSUER_FINGERPRINT_PATH), $threeDsMethodCompletionRequest, $this->getSdkAuthorization($sessionSecret));
    }

    /**
     * @throws CheckoutAuthorizationException
     */
    private function getSdkAuthorization($sessionSecret = null)
    {
        if (is_null($sessionSecret)) {
            return $this->sdkAuthorization();
        } else {
            $sdkAuthorization = new SessionSecretSdkCredentials($sessionSecret);
            return $sdkAuthorization->getAuthorization(AuthorizationType::$custom);
        }
    }

}
