<?php

namespace Checkout\Forward;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;
use Checkout\Forward\Requests\ForwardRequest;
use Checkout\Forward\Requests\CreateSecretRequest;
use Checkout\Forward\Requests\UpdateSecretRequest;

class ForwardClient extends Client
{
    const FORWARD_PATH = "forward";
    const SECRETS_PATH = "secrets";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$secretKeyOrOAuth);
    }

    /**
     * Forward an API request
     *
     * Forwards an API request to a third-party endpoint.
     * For example, you can forward payment credentials you've stored in our Vault to a third-party payment processor.
     * @param ForwardRequest $forwardRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function forwardAnApiRequest(ForwardRequest $forwardRequest)
    {
        return $this->apiClient->post(
            $this->buildPath(self::FORWARD_PATH),
            $forwardRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * Get forward request
     *
     * Retrieve the details of a successfully forwarded API request.
     * The details can be retrieved for up to 14 days after the request was initiated.
     * @param $forwardId
     * @return array
     * @throws CheckoutApiException
     */
    public function getForwardRequest($forwardId)
    {
        return $this->apiClient->get(
            $this->buildPath(self::FORWARD_PATH, $forwardId),
            $this->sdkAuthorization()
        );
    }

    /**
     * Create secret
     *
     * Create a new secret with a plaintext value.
     * @param CreateSecretRequest $createSecretRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function createSecret(CreateSecretRequest $createSecretRequest): array
    {
        return $this->apiClient->post(
            $this->buildPath(self::FORWARD_PATH, self::SECRETS_PATH),
            $createSecretRequest,
            $this->sdkAuthorization()
        );
    }

     /**
     * List secrets
     *
     * Returns metadata for secrets scoped for client_id.
     * @return array
     * @throws CheckoutApiException
     */
    public function listSecrets(): array
    {
        return $this->apiClient->get(
            $this->buildPath(self::FORWARD_PATH, self::SECRETS_PATH),
            $this->sdkAuthorization()
        );
    }

    /**
     * Update secret
     *
     * Update an existing secret. After updating, the version is automatically incremented.
     * @param string $name Secret name
     * @param UpdateSecretRequest $updateSecretRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function updateSecret(string $name, UpdateSecretRequest $updateSecretRequest): array
    {
        return $this->apiClient->patch(
            $this->buildPath(self::FORWARD_PATH, self::SECRETS_PATH, $name),
            $updateSecretRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * Delete secret
     *
     * Permanently delete a secret by name.
     * @param string $name Secret name
     * @return array
     * @throws CheckoutApiException
     */
    public function deleteSecret(string $name): array
    {
        return $this->apiClient->delete(
            $this->buildPath(self::FORWARD_PATH, self::SECRETS_PATH, $name),
            $this->sdkAuthorization()
        );
    }
}
