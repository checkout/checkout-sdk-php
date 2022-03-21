<?php

namespace Checkout;

use Psr\Log\LoggerInterface;

final class CheckoutConfiguration
{
    private $sdkCredentials;

    private $environment;

    private $httpClientBuilder;

    private $logger;

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

    public function getSdkCredentials()
    {
        return $this->sdkCredentials;
    }

    public function getEnvironment()
    {
        return $this->environment;
    }

    public function getHttpClientBuilder()
    {
        return $this->httpClientBuilder;
    }

    public function getLogger()
    {
        return $this->logger;
    }

}
