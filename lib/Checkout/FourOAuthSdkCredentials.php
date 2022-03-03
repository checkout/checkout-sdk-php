<?php

namespace Checkout;

use DateInterval;
use DateTime;
use GuzzleHttp\ClientInterface;
use Throwable;

class FourOAuthSdkCredentials implements SdkCredentialsInterface
{

    private ClientInterface $client;
    private string $clientId;
    private string $clientSecret;
    private string $authorizationUri;
    private array $scopes;
    private ?OAuthAccessToken $accessToken = null;

    /**
     * @param HttpClientBuilderInterface $httpClientBuilder
     * @param string $clientId
     * @param string $clientSecret
     * @param string $authorizationUri
     * @param array $scopes
     */
    public function __construct(HttpClientBuilderInterface $httpClientBuilder,
                                string                     $clientId,
                                string                     $clientSecret,
                                string                     $authorizationUri,
                                array                      $scopes)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->authorizationUri = $authorizationUri;
        $this->scopes = $scopes;
        $this->client = $httpClientBuilder->getClient();
    }

    /**
     * @param HttpClientBuilderInterface $httpClientBuilder
     * @param string $clientId
     * @param string $clientSecret
     * @param string $authorizationUri
     * @param array $scopes
     * @return FourOAuthSdkCredentials
     * @throws CheckoutException
     */
    public static function init(HttpClientBuilderInterface $httpClientBuilder,
                                string                     $clientId,
                                string                     $clientSecret,
                                string                     $authorizationUri,
                                array                      $scopes): FourOAuthSdkCredentials
    {
        $credentials = new FourOAuthSdkCredentials($httpClientBuilder, $clientId, $clientSecret, $authorizationUri, $scopes);
        $credentials->getAccessToken();
        return $credentials;
    }

    function getAuthorization(string $authorizationType): SdkAuthorization
    {
        switch ($authorizationType) {
            case AuthorizationType::$secretKeyOrOAuth:
            case AuthorizationType::$publicKeyOrOAuth:
            case AuthorizationType::$oAuth:
                return new SdkAuthorization(PlatformType::$fourOAuth, $this->getAccessToken()->getToken());
            default:
                throw CheckoutAuthorizationException::invalidAuthorization($authorizationType);
        }
    }

    private function getAccessToken(): OAuthAccessToken
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
        } catch (Throwable $e) {
            throw new CheckoutException($e->getMessage());
        }
    }
}
