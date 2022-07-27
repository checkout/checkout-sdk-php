<?php

namespace Checkout;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

abstract class AbstractCheckoutSdkBuilder
{

    protected $environment;
    protected $httpClientBuilder;
    protected $logger;

    public function __construct()
    {
        $this->environment = Environment::sandbox();
        $this->httpClientBuilder = new DefaultHttpClientBuilder();
        $this->setDefaultLogger();
    }

    /**
     * @param Environment $environment
     * @return $this
     */
    public function environment(Environment $environment)
    {
        $this->environment = $environment;
        return $this;
    }

    /**
     * @param HttpClientBuilderInterface $httpClientBuilder
     * @return $this
     */
    public function httpClientBuilder(HttpClientBuilderInterface $httpClientBuilder)
    {
        $this->httpClientBuilder = $httpClientBuilder;
        return $this;
    }

    /**
     * @param LoggerInterface $logger
     * @return $this
     */
    public function logger(LoggerInterface $logger)
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * @return CheckoutConfiguration
     */
    protected function getCheckoutConfiguration()
    {
        return new CheckoutConfiguration(
            $this->getSdkCredentials(),
            $this->environment,
            $this->httpClientBuilder,
            $this->logger
        );
    }

    private function setDefaultLogger()
    {
        $this->logger = new Logger(CheckoutUtils::PROJECT_NAME);
        $this->logger->pushHandler(new StreamHandler("php://stderr"));
    }

    abstract protected function getSdkCredentials();

    /**
     * @return mixed
     */
    abstract protected function build();
}
