<?php

namespace Checkout\Sessions;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;
use Checkout\SdkAuthorization;
use Checkout\Sessions\Channel\ChannelData;

class SessionsClient extends Client
{
    private const SESSIONS_PATH = "sessions";
    private const COLLECT_DATA_PATH = "collect-data";
    private const COMPLETE_PATH = "complete";
    private const ISSUER_FINGERPRINT_PATH = "issuer-fingerprint";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$oAuth);
    }

    /**
     * @param SessionRequest $sessionRequest
     * @return mixed
     * @throws CheckoutApiException
     */
    public function requestSession(SessionRequest $sessionRequest)
    {
        return $this->apiClient->post(self::SESSIONS_PATH, $sessionRequest, $this->sdkAuthorization());
    }

    /**
     * @param string $sessionId
     * @param string|null $sessionSecret
     * @return mixed
     * @throws CheckoutApiException
     * @throws CheckoutAuthorizationException
     */
    public function getSessionDetails(string $sessionId, string $sessionSecret = null)
    {
        return $this->apiClient->get($this->buildPath(self::SESSIONS_PATH, $sessionId), $this->getSdkAuthorization($sessionSecret));
    }

    /**
     * @param string $sessionId
     * @param ChannelData $channelData
     * @param string|null $sessionSecret
     * @return mixed
     * @throws CheckoutApiException
     * @throws CheckoutAuthorizationException
     */
    public function updateSession(string $sessionId, ChannelData $channelData, string $sessionSecret = null)
    {
        return $this->apiClient->put($this->buildPath(self::SESSIONS_PATH, $sessionId, self::COLLECT_DATA_PATH), $channelData, $this->getSdkAuthorization($sessionSecret));
    }

    /**
     * @param string $sessionId
     * @param string|null $sessionSecret
     * @return mixed
     * @throws CheckoutApiException
     * @throws CheckoutAuthorizationException
     */
    public function completeSession(string $sessionId, string $sessionSecret = null)
    {
        return $this->apiClient->post($this->buildPath(self::SESSIONS_PATH, $sessionId, self::COMPLETE_PATH), null, $this->getSdkAuthorization($sessionSecret));
    }

    /**
     * @param string $sessionId
     * @param ThreeDsMethodCompletionRequest $threeDsMethodCompletionRequest
     * @param string|null $sessionSecret
     * @return mixed
     * @throws CheckoutApiException
     * @throws CheckoutAuthorizationException
     */
    public function updateThreeDsMethodCompletionIndicator(string $sessionId, ThreeDsMethodCompletionRequest $threeDsMethodCompletionRequest, string $sessionSecret = null)
    {
        return $this->apiClient->put($this->buildPath(self::SESSIONS_PATH, $sessionId, self::ISSUER_FINGERPRINT_PATH), $threeDsMethodCompletionRequest, $this->getSdkAuthorization($sessionSecret));
    }

    /**
     * @throws CheckoutAuthorizationException
     */
    private function getSdkAuthorization(string $sessionSecret = null): SdkAuthorization
    {
        if (is_null($sessionSecret)) {
            return $this->sdkAuthorization();
        } else {
            $sdkAuthorization = new SessionSecretSdkCredentials($sessionSecret);
            return $sdkAuthorization->getAuthorization(AuthorizationType::$custom);
        }
    }

}
