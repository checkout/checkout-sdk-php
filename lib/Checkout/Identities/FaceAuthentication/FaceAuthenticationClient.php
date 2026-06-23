<?php

namespace Checkout\Identities\FaceAuthentication;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;
use Checkout\Identities\Entities\AttemptAssetsQueryFilter;
use Checkout\Identities\FaceAuthentication\Requests\FaceAuthenticationRequest;
use Checkout\Identities\FaceAuthentication\Requests\FaceAuthenticationAttemptRequest;

class FaceAuthenticationClient extends Client
{
    const FACE_AUTHENTICATIONS_PATH = "face-authentications";
    const ANONYMIZE_PATH = "anonymize";
    const ATTEMPTS_PATH = "attempts";
    const ASSETS_PATH = "assets";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$secretKeyOrOAuth);
    }

    /**
     * @param FaceAuthenticationRequest $faceAuthenticationRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function createFaceAuthentication(FaceAuthenticationRequest $faceAuthenticationRequest): array
    {
        return $this->apiClient->post(
            self::FACE_AUTHENTICATIONS_PATH,
            $faceAuthenticationRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * faceAuthenticationId is the face authentication's unique identifier. (Required)
     *
     * @param string $faceAuthenticationId
     * @return array
     * @throws CheckoutApiException
     */
    public function getFaceAuthentication(string $faceAuthenticationId): array
    {
        return $this->apiClient->get(
            $this->buildPath(self::FACE_AUTHENTICATIONS_PATH, $faceAuthenticationId),
            $this->sdkAuthorization()
        );
    }

    /**
     * faceAuthenticationId is the face authentication's unique identifier. (Required)
     *
     * @param string $faceAuthenticationId
     * @return array
     * @throws CheckoutApiException
     */
    public function anonymizeFaceAuthentication(string $faceAuthenticationId): array
    {
        return $this->apiClient->post(
            $this->buildPath(self::FACE_AUTHENTICATIONS_PATH, $faceAuthenticationId, self::ANONYMIZE_PATH),
            null,
            $this->sdkAuthorization()
        );
    }

    /**
     * faceAuthenticationId is the face authentication's unique identifier. (Required)
     *
     * @param string $faceAuthenticationId
     * @param FaceAuthenticationAttemptRequest $faceAuthenticationAttemptRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function createFaceAuthenticationAttempt(
        string $faceAuthenticationId,
        FaceAuthenticationAttemptRequest $faceAuthenticationAttemptRequest
    ): array {
        return $this->apiClient->post(
            $this->buildPath(self::FACE_AUTHENTICATIONS_PATH, $faceAuthenticationId, self::ATTEMPTS_PATH),
            $faceAuthenticationAttemptRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * faceAuthenticationId is the face authentication's unique identifier. (Required)
     *
     * @param string $faceAuthenticationId
     * @return array
     * @throws CheckoutApiException
     */
    public function getFaceAuthenticationAttempts(string $faceAuthenticationId): array
    {
        return $this->apiClient->get(
            $this->buildPath(self::FACE_AUTHENTICATIONS_PATH, $faceAuthenticationId, self::ATTEMPTS_PATH),
            $this->sdkAuthorization()
        );
    }

    /**
     * faceAuthenticationId is the face authentication's unique identifier. (Required)
     * attemptId is the attempt's unique identifier. (Required)
     *
     * @param string $faceAuthenticationId
     * @param string $attemptId
     * @return array
     * @throws CheckoutApiException
     */
    public function getFaceAuthenticationAttempt(string $faceAuthenticationId, string $attemptId): array
    {
        return $this->apiClient->get(
            $this->buildPath(self::FACE_AUTHENTICATIONS_PATH, $faceAuthenticationId, self::ATTEMPTS_PATH, $attemptId),
            $this->sdkAuthorization()
        );
    }

    /**
     * Retrieves the assets (face images and videos) captured during a face authentication attempt.
     *
     * faceAuthenticationId is the face authentication's unique identifier. (Required)
     * attemptId is the attempt's unique identifier. (Required)
     *
     * @param string $faceAuthenticationId
     * @param string $attemptId
     * @param AttemptAssetsQueryFilter|null $query the pagination query parameters (skip and limit)
     * @return array
     * @throws CheckoutApiException
     */
    public function getFaceAuthenticationAttemptAssets(
        string $faceAuthenticationId,
        string $attemptId,
        ?AttemptAssetsQueryFilter $query = null
    ): array {
        return $this->apiClient->query(
            $this->buildPath(self::FACE_AUTHENTICATIONS_PATH, $faceAuthenticationId, self::ATTEMPTS_PATH, $attemptId, self::ASSETS_PATH),
            $query,
            $this->sdkAuthorization()
        );
    }
}
