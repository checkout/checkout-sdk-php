<?php

namespace Checkout;

class CheckoutOAuthSdkBuilder extends AbstractCheckoutSdkBuilder
{

    protected $clientId = null;
    protected $clientSecret = null;
    protected $authorizationUri = null;
    protected $scopes = array();

    /**
     * @param string $clientId
     * @param string $clientSecret
     * @return $this
     */
    public function clientCredentials(
        $clientId,
        $clientSecret
    ) {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        return $this;
    }

    /**
     * @param string $authorizationUri
     * @return $this
     */
    public function authorizationUri($authorizationUri)
    {
        $this->authorizationUri = $authorizationUri;
        return $this;
    }

    /**
     * @param array $scopes
     * @return $this
     */
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
            throw new CheckoutArgumentException(
                "Invalid configuration. Please specify valid 'client_id' and 'client_secret' configurations."
            );
        }
        if (empty($this->authorizationUri)) {
            if (is_null($this->environment)) {
                throw new CheckoutArgumentException(
                    "Invalid configuration. Please specify an Environment or a specific OAuth authorization URI."
                );
            }
            $this->authorizationUri = $this->environment->getAuthorizationUri();
        }
        return OAuthSdkCredentials::init(
            $this->httpClientBuilder,
            $this->clientId,
            $this->clientSecret,
            $this->authorizationUri,
            $this->scopes
        );
    }

    /**
     * @return CheckoutApi
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    public function build()
    {
        $configuration = new CheckoutConfiguration(
            $this->getSdkCredentials(),
            $this->environment,
            $this->httpClientBuilder,
            $this->logger
        );
        return new CheckoutApi($configuration);
    }
}
