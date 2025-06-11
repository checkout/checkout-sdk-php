<?php

namespace Checkout;

use Checkout\Common\AbstractQueryFilter;
use Checkout\Files\FileRequest;
use Exception;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;

class ApiClient
{
    private $configuration;
    private $client;
    private $jsonSerializer;
    private $logger;
    private $baseUri;

    public function __construct(CheckoutConfiguration $configuration, ?string $baseUri = null)
    {
        $this->configuration = $configuration;
        $this->client = $configuration->getHttpClientBuilder()->getClient();
        $this->jsonSerializer = new JsonSerializer();
        $this->logger = $this->configuration->getLogger();
        $this->baseUri = $baseUri !== null
            ? $baseUri
            : $this->configuration->getEnvironment()->getBaseUri();
    }

    /**
     * @param string $path
     * @param SdkAuthorization $authorization
     * @return array
     * @throws CheckoutApiException
     */
    public function get(string $path, SdkAuthorization $authorization): array
    {
        return $this->invoke("GET", $path, null, $authorization);
    }

    /**
     * @param string $path
     * @param mixed $body
     * @param SdkAuthorization $authorization
     * @param string|null $idempotencyKey
     * @return array
     * @throws CheckoutApiException
     */
    public function post(
        string $path,
        $body,
        SdkAuthorization $authorization,
        ?string $idempotencyKey = null
    ): array {
        return $this->invoke(
            "POST",
            $path,
            $body === null ? null : $this->jsonSerializer->serialize($body),
            $authorization,
            $idempotencyKey
        );
    }

    /**
     * @param string $path
     * @param mixed $body
     * @param SdkAuthorization $authorization
     * @return array
     * @throws CheckoutApiException
     */
    public function put(string $path, $body, SdkAuthorization $authorization): array
    {
        return $this->invoke("PUT", $path, $this->jsonSerializer->serialize($body), $authorization);
    }

    /**
     * @param string $path
     * @param mixed $body
     * @param SdkAuthorization $authorization
     * @return array
     * @throws CheckoutApiException
     */
    public function patch(string $path, $body, SdkAuthorization $authorization): array
    {
        return $this->invoke("PATCH", $path, $this->jsonSerializer->serialize($body), $authorization);
    }

    /**
     * @param string $path
     * @param SdkAuthorization $authorization
     * @return array
     * @throws CheckoutApiException
     */
    public function delete(string $path, SdkAuthorization $authorization): array
    {
        return $this->invoke("DELETE", $path, null, $authorization);
    }

    /**
     * @param string $path
     * @param AbstractQueryFilter $body
     * @param SdkAuthorization $authorization
     * @return array
     * @throws CheckoutApiException
     */
    public function query(
        string $path,
        AbstractQueryFilter $body,
        SdkAuthorization $authorization
    ): array {
        $this->logger->info("GET " . $path);
        $queryParameters = $body->getEncodedQueryParameters();
        if (!empty($queryParameters)) {
            $path .= "?" . $queryParameters;
        }
        return $this->invoke("GET", $path, null, $authorization);
    }

    /**
     * @param string $path
     * @param FileRequest $fileRequest
     * @param SdkAuthorization $authorization
     * @return array
     * @throws CheckoutApiException
     */
    public function submitFile(
        string $path,
        FileRequest $fileRequest,
        SdkAuthorization $authorization
    ): array {
        return $this->submit($path, $fileRequest, $authorization, "file");
    }

    /**
     * @param string $path
     * @param FileRequest $fileRequest
     * @param SdkAuthorization $authorization
     * @return array
     * @throws CheckoutApiException
     */
    public function submitFileFilesApi(
        string $path,
        FileRequest $fileRequest,
        SdkAuthorization $authorization
    ): array {
        return $this->submit($path, $fileRequest, $authorization, "path");
    }

    /**
     * @param string $path
     * @param FileRequest $fileRequest
     * @param SdkAuthorization $authorization
     * @param string $multipart
     * @return array
     * @throws CheckoutApiException
     */
    private function submit(
        string $path,
        FileRequest $fileRequest,
        SdkAuthorization $authorization,
        string $multipart
    ): array {
        try {
            $this->logger->info("POST " . $path . " file: " . $fileRequest->file);
            $headers = $this->getHeaders($authorization, null, null);
            $response = $this->client->request("POST", $this->getRequestUrl($path), [
                "verify" => false,
                "headers" => $headers,
                "multipart" => [
                    [
                        "name" => $multipart,
                        "contents" => fopen($fileRequest->file, "r")
                    ],
                    [
                        "name" => "purpose",
                        "contents" => $fileRequest->purpose
                    ]
                ]
            ]);
            return $this->getResponseContents($response);
        } catch (Exception $e) {
            $this->logger->error($path . " error: " . $e->getMessage());
            if ($e instanceof RequestException) {
                throw CheckoutApiException::from($e);
            }
            throw new CheckoutApiException($e);
        }
    }

    /**
     * @param string $method
     * @param string $path
     * @param mixed $body
     * @param SdkAuthorization $authorization
     * @param string|null $idempotencyKey
     * @return array
     * @throws CheckoutApiException
     */
    private function invoke(
        string $method,
        string $path,
        $body,
        SdkAuthorization $authorization,
        ?string $idempotencyKey = null
    ): array {
        try {
            $this->logger->info($method . " " . $path);
            $headers = $this->getHeaders($authorization, "application/json", $idempotencyKey);
            $response = $this->client->request($method, $this->getRequestUrl($path), [
                "verify" => false,
                "body" => $body,
                "headers" => $headers
            ]);
            return $this->getResponseContents($response);
        } catch (Exception $e) {
            $this->logger->error($path . " error: " . $e->getMessage());
            if ($e instanceof RequestException) {
                throw CheckoutApiException::from($e);
            }
            throw new CheckoutApiException($e);
        }
    }

    private function getRequestUrl(string $path): string
    {
        return $this->baseUri . $path;
    }

    /**
     * @param SdkAuthorization $authorization
     * @param string|null $contentType
     * @param string|null $idempotencyKey
     * @return array
     * @throws CheckoutAuthorizationException
     */
    private function getHeaders(
        SdkAuthorization $authorization,
        ?string $contentType,
        ?string $idempotencyKey
    ): array {
        $headers = [
            "User-agent" => CheckoutUtils::PROJECT_NAME . "/" . CheckoutUtils::PROJECT_VERSION,
            "Accept" => "application/json",
            "Authorization" => $authorization->getAuthorizationHeader()
        ];
        if (!empty($contentType)) {
            $headers["Content-Type"] = $contentType;
        }
        if (!empty($idempotencyKey)) {
            $headers["Cko-Idempotency-Key"] = $idempotencyKey;
        }
        return $headers;
    }

    /**
     * @param Response $response
     * @return array
     */
    private function getResponseContents(Response $response): array
    {
        $contentType = $response->getHeader("Content-Type");
        if (in_array("text/csv", $contentType)) {
            return $this->createResponse($response, $response->getBody()->getContents());
        }
        return $this->createResponse($response, $this->jsonSerializer->deserialize($response->getBody()));
    }

    /**
     * @param Response|null $http_response
     * @param string|array|null $data
     * @return array
     */
    private static function createResponse(?Response $http_response = null, $data = null): array
    {
        $response = [];
        if ($http_response !== null) {
            $response["http_metadata"] = CheckoutUtils::getHttpMetadata($http_response);
        }
        if ($data !== null) {
            if (is_array($data)) {
                if (array_keys($data) !== range(0, count($data) - 1)) {
                    $response = array_merge($response, $data);
                } else {
                    $response["items"] = $data;
                }
            }
            if (is_string($data)) {
                $response["contents"] = $data;
            }
        }
        return $response;
    }
}
