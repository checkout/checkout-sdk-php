<?php

namespace Checkout;

use DateInterval;
use DateTime;
use Exception;

class OAuthSdkCredentials implements SdkCredentialsInterface
{

    private $client;
    private $clientId;
    private $clientSecret;
    private $authorizationUri;
    private $scopes;
    private $accessToken = null;

    /**
     * @param HttpClientBuilderInterface $httpClientBuilder
     * @param $clientId
     * @param $clientSecret
     * @param $authorizationUri
     * @param array $scopes
     */
    public function __construct(
        HttpClientBuilderInterface $httpClientBuilder,
        $clientId,
        $clientSecret,
        $authorizationUri,
        array                      $scopes
    ) {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->authorizationUri = $authorizationUri;
        $this->scopes = $scopes;
        $this->client = $httpClientBuilder->getClient();
    }

    /**
     * @param HttpClientBuilderInterface $httpClientBuilder
     * @param $clientId
     * @param $clientSecret
     * @param $authorizationUri
     * @param array $scopes
     * @return OAuthSdkCredentials
     * @throws CheckoutException
     */
    public static function init(
        HttpClientBuilderInterface $httpClientBuilder,
        $clientId,
        $clientSecret,
        $authorizationUri,
        array                      $scopes
    ) {
        $credentials = new OAuthSdkCredentials(
            $httpClientBuilder,
            $clientId,
            $clientSecret,
            $authorizationUri,
            $scopes
        );
        $credentials->getAccessToken();
        return $credentials;
    }

    /**
     * @throws CheckoutAuthorizationException
     * @throws CheckoutException
     */
    public function getAuthorization($authorizationType)
    {
        switch ($authorizationType) {
            case AuthorizationType::$secretKeyOrOAuth:
            case AuthorizationType::$publicKeyOrOAuth:
            case AuthorizationType::$oAuth:
                return new SdkAuthorization(PlatformType::$default_oauth, $this->getAccessToken()->getToken());
            default:
                throw CheckoutAuthorizationException::invalidAuthorization($authorizationType);
        }
    }

    /**
     * @throws CheckoutException
     */
    private function getAccessToken()
    {
        if (!is_null($this->accessToken) && $this->accessToken->isValid()) {
            return $this->accessToken;
        }
        try {
            $response = $this->client->request("POST", $this->authorizationUri, [
                "verify" => false,
                "headers" => [
                    "Content-Type" => "application/x-www-form-urlencoded"
                ],
                "form_params" => [
                    "client_id" => $this->clientId,
                    "client_secret" => $this->clientSecret,
                    "grant_type" => "client_credentials",
                    "scope" => implode(" ", $this->scopes)
                ]
            ]);
            $body = json_decode($response->getBody(), true);
            $expirationDate = new DateTime();
            $expirationDate->add(new DateInterval("PT" . $body["expires_in"] . "S"));
            $this->accessToken = new OAuthAccessToken($body["access_token"], $expirationDate);
            return $this->accessToken;
        } catch (Exception $e) {
            throw new CheckoutException($e->getMessage());
        }
    }
}
