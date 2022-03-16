<?php

namespace Checkout;

use Psr\Log\LoggerInterface;

final class CheckoutConfiguration
{
    private SdkCredentialsInterface $sdkCredentials;

    private Environment $environment;

    private HttpClientBuilderInterface $httpClientBuilder;

    private LoggerInterface $logger;

    public function __construct(SdkCredentialsInterface    $sdkCredentials,
                                Environment                $environment,
                                HttpClientBuilderInterface $httpClientBuilder,
                                LoggerInterface            $logger)
    {
        $this->sdkCredentials = $sdkCredentials;
        $this->environment = $environment;
        $this->httpClientBuilder = $httpClientBuilder;
        $this->logger = $logger;
    }

    public function getSdkCredentials(): SdkCredentialsInterface
    {
        return $this->sdkCredentials;
    }

    public function getEnvironment(): Environment
    {
        return $this->environment;
    }

    public function getHttpClientBuilder(): HttpClientBuilderInterface
    {
        return $this->httpClientBuilder;
    }

    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

}
