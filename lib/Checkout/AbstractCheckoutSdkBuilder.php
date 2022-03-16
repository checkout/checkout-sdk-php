<?php

namespace Checkout;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

abstract class AbstractCheckoutSdkBuilder
{

    protected ?Environment $environment;
    protected HttpClientBuilderInterface $httpClientBuilder;
    protected LoggerInterface $logger;

    public function __construct()
    {
        $this->environment = Environment::sandbox();
        $this->httpClientBuilder = new DefaultHttpClientBuilder();
        $this->setDefaultLogger();
    }

    public function setEnvironment(Environment $environment): void
    {
        $this->environment = $environment;
    }

    public function setHttpClientBuilder(HttpClientBuilderInterface $httpClientBuilder): void
    {
        $this->httpClientBuilder = $httpClientBuilder;
    }

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    protected function getCheckoutConfiguration(): CheckoutConfiguration
    {
        return new CheckoutConfiguration($this->getSdkCredentials(), $this->environment,
            $this->httpClientBuilder, $this->logger);
    }

    private function setDefaultLogger(): void
    {
        $this->logger = new Logger(CheckoutUtils::PROJECT_NAME);
        $this->logger->pushHandler(new StreamHandler("php://stderr"));
    }

    protected abstract function getSdkCredentials(): SdkCredentialsInterface;

    /**
     * @return mixed
     */
    protected abstract function build();

}
