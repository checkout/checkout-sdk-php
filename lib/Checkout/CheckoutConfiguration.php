<?php

namespace Checkout;

use Psr\Log\LoggerInterface;

final class CheckoutConfiguration
{
    private $sdkCredentials;

    private $environment;

    private $environmentSubdomain;

    private $httpClientBuilder;

    private $logger;
    
    private $enableTelemetry;

    /**
     * @param SdkCredentialsInterface $sdkCredentials
     * @param Environment $environment
     * @param HttpClientBuilderInterface $httpClientBuilder
     * @param LoggerInterface $logger
     * @param bool $enableTelemetry
     */
    public function __construct(
        SdkCredentialsInterface    $sdkCredentials,
        Environment                $environment,
        HttpClientBuilderInterface $httpClientBuilder,
        LoggerInterface            $logger,
        $enableTelemetry = true
    ) {
        $this->sdkCredentials = $sdkCredentials;
        $this->environment = $environment;
        $this->httpClientBuilder = $httpClientBuilder;
        $this->logger = $logger;
        $this->environmentSubdomain = null;
        $this ->enableTelemetry = $enableTelemetry;
    }

    /**
     * @return SdkCredentialsInterface
     */
    public function getSdkCredentials()
    {
        return $this->sdkCredentials;
    }

    /**
     * @return Environment
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @return EnvironmentSubdomain|null
     */
    public function getEnvironmentSubdomain()
    {
        return $this->environmentSubdomain;
    }

    public function setEnvironmentSubdomain(EnvironmentSubdomain $environmentSubdomain)
    {
        $this->environmentSubdomain = $environmentSubdomain;
    }

    /**
     * @return HttpClientBuilderInterface
     */
    public function getHttpClientBuilder()
    {
        return $this->httpClientBuilder;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @return bool
     */
    public function isEnableTelemetry()
    {
        return $this->enableTelemetry;
    }
}
