<?php

namespace Checkout;

use DateInterval;
use DateTime;
use Exception;

class OAuthSdkCredentials implements SdkCredentialsInterface
{

    /**
     * Upper bound accepted for expires_in (one year). Real tokens last about an hour; anything
     * larger is treated as an invalid response instead of overflowing DateInterval.
     */
    private const MAX_EXPIRES_IN_SECONDS = 31536000;

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
     * @throws CheckoutAuthorizationException
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
     * @throws CheckoutAuthorizationException
     * @throws CheckoutException
     */
    private function getAccessToken()
    {
        if (!is_null($this->accessToken) && $this->accessToken->isValid()) {
            return $this->accessToken;
        }
        try {
            $response = $this->client->request("POST", $this->authorizationUri, [
                "headers" => [
                    "Content-Type" => "application/x-www-form-urlencoded"
                ],
                "form_params" => [
                    "grant_type" => "client_credentials",
                    "client_id" => $this->clientId,
                    "client_secret" => $this->clientSecret,
                    "scope" => implode(" ", $this->scopes)
                ]
            ]);
        } catch (Exception $e) {
            throw new CheckoutException($e->getMessage());
        }
        $body = json_decode((string)$response->getBody(), true);
        if (!self::isValidTokenResponse($body)) {
            throw CheckoutAuthorizationException::invalidAccessTokenResponse();
        }
        $expirationDate = new DateTime();
        $expirationDate->add(new DateInterval("PT" . (int)$body["expires_in"] . "S"));
        $tokenType = $body["token_type"] ?? null;
        $this->accessToken = new OAuthAccessToken($body["access_token"], $tokenType, $expirationDate);
        return $this->accessToken;
    }

    /**
     * @param mixed $body decoded token response
     * @return bool true when it carries a non-empty access_token and a numeric expires_in
     *     between 0 and one year, compared as a float so huge values cannot overflow the int cast
     */
    private static function isValidTokenResponse($body)
    {
        return is_array($body)
            && isset($body["access_token"], $body["expires_in"])
            && is_string($body["access_token"])
            && $body["access_token"] !== ""
            && is_numeric($body["expires_in"])
            && (float)$body["expires_in"] >= 0
            && (float)$body["expires_in"] <= self::MAX_EXPIRES_IN_SECONDS;
    }
}
