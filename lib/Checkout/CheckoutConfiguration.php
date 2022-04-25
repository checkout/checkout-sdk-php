<?php

namespace Checkout;

use Psr\Log\LoggerInterface;

final class CheckoutConfiguration
{
    private $sdkCredentials;

    private $environment;

    private $httpClientBuilder;

    private $logger;

    /**
     * @param SdkCredentialsInterface $sdkCredentials
     * @param Environment $environment
     * @param HttpClientBuilderInterface $httpClientBuilder
     * @param LoggerInterface $logger
     */
    public function __construct(
        SdkCredentialsInterface    $sdkCredentials,
        Environment                $environment,
        HttpClientBuilderInterface $httpClientBuilder,
        LoggerInterface            $logger
    ) {
        $this->sdkCredentials = $sdkCredentials;
        $this->environment = $environment;
        $this->httpClientBuilder = $httpClientBuilder;
        $this->logger = $logger;
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
}
