<?php

namespace Checkout;

use Psr\Log\LoggerInterface;

final class CheckoutConfiguration
{
    private SdkCredentialsInterface $sdkCredentials;

    private Environment $environment;

    private HttpClientBuilderInterface $httpClientBuilder;

    private LoggerInterface $logger;

    private ?CheckoutFilesConfiguration $filesConfiguration = null;

    public function __construct(SdkCredentialsInterface    $sdkCredentials,
                                Environment                $environment,
                                HttpClientBuilderInterface $httpClientBuilder,
                                LoggerInterface            $logger,
                                Environment                $filesEnvironment = null)
    {
        $this->sdkCredentials = $sdkCredentials;
        $this->environment = $environment;
        $this->httpClientBuilder = $httpClientBuilder;
        $this->logger = $logger;
        if ($filesEnvironment) {
            $this->filesConfiguration = new CheckoutFilesConfiguration($filesEnvironment);
        }
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

    public function getFilesConfiguration(): ?CheckoutFilesConfiguration
    {
        return $this->filesConfiguration;
    }

}
