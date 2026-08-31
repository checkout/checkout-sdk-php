<?php

namespace Checkout;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

abstract class AbstractCheckoutSdkBuilder
{

    protected $environment;
    protected $subdomain = null;
    private $environmentSubdomain = null;
    protected $useLegacyDomain = false;
    protected $httpClientBuilder;
    protected $logger;

    public function __construct()
    {
        $this->environment = Environment::sandbox();
        $this->httpClientBuilder = new DefaultHttpClientBuilder([]);
        $this->setDefaultLogger();
    }

    /**
     * @param Environment $environment
     * @return $this
     */
    public function environment(Environment $environment)
    {
        $this->environment = $environment;
        $this->environmentSubdomain = null;
        return $this;
    }

    /**
     * @param $subdomain
     * @return $this
     */
    public function environmentSubdomain($subdomain)
    {
        $this->subdomain = $subdomain;
        $this->environmentSubdomain = null;
        return $this;
    }

    /**
     * Opts out of the merchant-specific subdomain, sending every request to the shared
     * hosts instead (api.checkout.com and access.checkout.com, or their sandbox equivalents).
     *
     * @deprecated this is an emergency fallback for the rare case where the merchant-specific
     * subdomain cannot be used, and will be removed in a future release. Call
     * environmentSubdomain() instead. See https://api-reference.checkout.com/#section/Base-URLs
     * @return $this
     */
    public function useLegacyDomain()
    {
        $this->useLegacyDomain = true;
        return $this;
    }

    /**
     * @return EnvironmentSubdomain|null
     * @throws CheckoutArgumentException
     */
    protected function getEnvironmentSubdomain()
    {
        if ($this->subdomain === null) {
            return null;
        }
        if ($this->environmentSubdomain === null) {
            $this->environmentSubdomain = new EnvironmentSubdomain($this->environment, $this->subdomain);
        }
        return $this->environmentSubdomain;
    }

    /**
     * Whether this builder requires the merchant-specific subdomain to be configured. The
     * Previous (ABC) platform predates merchant-specific subdomains, so it overrides this
     * to false.
     *
     * @return bool
     */
    protected function requiresEnvironmentSubdomain()
    {
        return true;
    }

    /**
     * @throws CheckoutArgumentException
     */
    protected function validateEnvironmentSettings()
    {
        if ($this->subdomain !== null && $this->useLegacyDomain) {
            throw new CheckoutArgumentException(
                "environmentSubdomain and useLegacyDomain cannot both be set - provide only your " .
                "merchant-specific subdomain"
            );
        }
        if ($this->subdomain === null && !$this->useLegacyDomain && $this->requiresEnvironmentSubdomain()) {
            throw new CheckoutArgumentException(
                "environmentSubdomain is required - provide your merchant-specific subdomain (typically your " .
                "client ID excluding the cli_ prefix, see https://api-reference.checkout.com/#section/Base-URLs), " .
                "or call useLegacyDomain() to opt out only if merchant specific sub domains are causing issues"
            );
        }
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
