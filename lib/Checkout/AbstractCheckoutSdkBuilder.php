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
     */
    public function setEnvironment(Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * @param HttpClientBuilderInterface $httpClientBuilder
     */
    public function setHttpClientBuilder(HttpClientBuilderInterface $httpClientBuilder)
    {
        $this->httpClientBuilder = $httpClientBuilder;
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
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
