<?php

namespace Checkout;

abstract class Client
{
    protected $apiClient;

    protected $configuration;

    private $sdkAuthorizationType;

    /**
     * @param ApiClient $apiClient
     * @param CheckoutConfiguration $configuration
     * @param $sdkAuthorizationType
     */
    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration, $sdkAuthorizationType)
    {
        $this->apiClient = $apiClient;
        $this->configuration = $configuration;
        $this->sdkAuthorizationType = $sdkAuthorizationType;
    }

    /**
     * @return mixed
     */
    protected function sdkAuthorization()
    {
        return $this->configuration->getSdkCredentials()->getAuthorization($this->sdkAuthorizationType);
    }

    /**
     * @param $authorizationType
     * @return mixed
     */
    protected function sdkSpecificAuthorization($authorizationType)
    {
        return $this->configuration->getSdkCredentials()->getAuthorization($authorizationType);
    }

    /**
     * @param mixed ...$parts
     * @return string
     */
    protected function buildPath(...$parts)
    {
        return join("/", $parts);
    }
}
