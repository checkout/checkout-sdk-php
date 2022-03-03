<?php

namespace Checkout;

abstract class Client
{
    protected ApiClient $apiClient;

    protected CheckoutConfiguration $configuration;

    private string $sdkAuthorizationType;

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration, string $sdkAuthorizationType)
    {
        $this->apiClient = $apiClient;
        $this->configuration = $configuration;
        $this->sdkAuthorizationType = $sdkAuthorizationType;
    }

    protected function sdkAuthorization(): SdkAuthorization
    {
        return $this->configuration->getSdkCredentials()->getAuthorization($this->sdkAuthorizationType);
    }

    protected function sdkSpecificAuthorization(string $authorizationType): SdkAuthorization
    {
        return $this->configuration->getSdkCredentials()->getAuthorization($authorizationType);
    }

    /**
     * @param mixed ...$parts
     * @return string
     */
    protected function buildPath(...$parts): string
    {
        return join("/", $parts);
    }

}
