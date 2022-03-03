<?php

namespace Checkout;

class FourOAuthCheckoutSdkBuilder extends AbstractCheckoutSdkBuilder
{

    protected string $clientId;
    protected string $clientSecret;
    protected ?string $authorizationUri = null;
    protected array $scopes = array();

    public function clientCredentials(string $clientId,
                                      string $clientSecret): FourOAuthCheckoutSdkBuilder
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        return $this;
    }

    public function authorizationUri(string $authorizationUri): FourOAuthCheckoutSdkBuilder
    {
        $this->authorizationUri = $authorizationUri;
        return $this;
    }

    public function scopes(array $scopes): FourOAuthCheckoutSdkBuilder
    {
        $this->scopes = $scopes;
        return $this;
    }

    /**
     * @return SdkCredentialsInterface
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    protected function getSdkCredentials(): SdkCredentialsInterface
    {
        if (is_null($this->authorizationUri)) {
            if (is_null($this->environment)) {
                throw new CheckoutArgumentException("Invalid configuration. Please specify an Environment or a specific OAuth authorizationURI.");
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
    public function build(): Four\CheckoutApi
    {
        $configuration = new CheckoutConfiguration($this->getSdkCredentials(), $this->environment, $this->httpClientBuilder, $this->logger, $this->filesEnvironment);
        $apiClient = new ApiClient($configuration);
        return new Four\CheckoutApi($apiClient, $configuration);
    }
}
