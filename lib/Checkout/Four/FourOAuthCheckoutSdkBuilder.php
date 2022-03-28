<?php

namespace Checkout\Four;

use Checkout\AbstractCheckoutSdkBuilder;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutConfiguration;
use Checkout\CheckoutException;
use Checkout\Four;
use Checkout\SdkCredentialsInterface;

class FourOAuthCheckoutSdkBuilder extends AbstractCheckoutSdkBuilder
{

    protected $clientId = null;
    protected $clientSecret = null;
    protected $authorizationUri = null;
    protected $scopes = array();

    public function clientCredentials($clientId,
                                      $clientSecret)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        return $this;
    }

    public function authorizationUri($authorizationUri)
    {
        $this->authorizationUri = $authorizationUri;
        return $this;
    }

    public function scopes(array $scopes)
    {
        $this->scopes = $scopes;
        return $this;
    }

    /**
     * @return SdkCredentialsInterface
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    protected function getSdkCredentials()
    {
        if (empty($this->clientId) || empty($this->clientSecret)) {
            throw new CheckoutArgumentException("Invalid configuration. Please specify valid 'client_id' and 'client_secret' configurations.");
        }
        if (empty($this->authorizationUri)) {
            if (is_null($this->environment)) {
                throw new CheckoutArgumentException("Invalid configuration. Please specify an Environment or a specific OAuth authorization URI.");
            }
            $this->authorizationUri = $this->environment->getAuthorizationUri();
        }
        return FourOAuthSdkCredentials::init($this->httpClientBuilder, $this->clientId, $this->clientSecret, $this->authorizationUri, $this->scopes);
    }

    /**
     * @return Four\CheckoutApi
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    public function build()
    {
        $configuration = new CheckoutConfiguration($this->getSdkCredentials(), $this->environment, $this->httpClientBuilder, $this->logger);
        return new Four\CheckoutApi($configuration);
    }
}