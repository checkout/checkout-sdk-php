<?php

namespace Checkout\Sessions;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;
use Checkout\SdkAuthorization;
use Checkout\Sessions\Channel\ChannelData;

class SessionsClient extends Client
{
    const SESSIONS_PATH = "sessions";
    const COLLECT_DATA_PATH = "collect-data";
    const COMPLETE_PATH = "complete";
    const ISSUER_FINGERPRINT_PATH = "issuer-fingerprint";

    public function __construct(
        ApiClient $apiClient,
        CheckoutConfiguration $configuration
    ) {
        parent::__construct($apiClient, $configuration, AuthorizationType::$oAuth);
    }

    /**
     * @param SessionRequest $sessionRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function requestSession(SessionRequest $sessionRequest): array
    {
        return $this->apiClient->post(
            self::SESSIONS_PATH,
            $sessionRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param string $sessionId
     * @param string|null $sessionSecret
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutAuthorizationException
     * @throws CheckoutArgumentException
     */
    public function getSessionDetails(string $sessionId, ?string $sessionSecret = null): array
    {
        return $this->apiClient->get(
            $this->buildPath(self::SESSIONS_PATH, $this->encodePathSegment($sessionId)),
            $this->getSdkAuthorization($sessionSecret)
        );
    }

    /**
     * @param string $sessionId
     * @param ChannelData $channelData
     * @param string|null $sessionSecret
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutAuthorizationException
     * @throws CheckoutArgumentException
     */
    public function updateSession(
        string $sessionId,
        ChannelData $channelData,
        ?string $sessionSecret = null
    ): array {
        return $this->apiClient->put(
            $this->buildPath(self::SESSIONS_PATH, $this->encodePathSegment($sessionId), self::COLLECT_DATA_PATH),
            $channelData,
            $this->getSdkAuthorization($sessionSecret)
        );
    }

    /**
     * @param string $sessionId
     * @param string|null $sessionSecret
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutAuthorizationException
     * @throws CheckoutArgumentException
     */
    public function completeSession(string $sessionId, ?string $sessionSecret = null): array
    {
        return $this->apiClient->post(
            $this->buildPath(self::SESSIONS_PATH, $this->encodePathSegment($sessionId), self::COMPLETE_PATH),
            null,
            $this->getSdkAuthorization($sessionSecret)
        );
    }

    /**
     * @param string $sessionId
     * @param ThreeDsMethodCompletionRequest $threeDsMethodCompletionRequest
     * @param string|null $sessionSecret
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutAuthorizationException
     * @throws CheckoutArgumentException
     */
    public function updateThreeDsMethodCompletionIndicator(
        string $sessionId,
        ThreeDsMethodCompletionRequest $threeDsMethodCompletionRequest,
        ?string $sessionSecret = null
    ): array {
        return $this->apiClient->put(
            $this->buildPath(self::SESSIONS_PATH, $this->encodePathSegment($sessionId), self::ISSUER_FINGERPRINT_PATH),
            $threeDsMethodCompletionRequest,
            $this->getSdkAuthorization($sessionSecret)
        );
    }

    /**
     * @param string|null $sessionSecret
     * @return SdkAuthorization
     * @throws CheckoutAuthorizationException
     */
    private function getSdkAuthorization(?string $sessionSecret = null): SdkAuthorization
    {
        if ($sessionSecret === null) {
            return $this->sdkAuthorization();
        }

        $sdkAuthorization = new SessionSecretSdkCredentials($sessionSecret);
        return $sdkAuthorization->getAuthorization(AuthorizationType::$custom);
    }
}
