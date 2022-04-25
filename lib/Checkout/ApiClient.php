<?php

namespace Checkout;

use Checkout\Common\AbstractQueryFilter;
use Checkout\Files\FileRequest;
use Exception;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

class ApiClient
{

    private $configuration;

    private $client;

    private $jsonSerializer;

    private $logger;

    private $baseUri;

    public function __construct(CheckoutConfiguration $configuration, $baseUri = null)
    {
        $this->configuration = $configuration;
        $this->client = $configuration->getHttpClientBuilder()->getClient();
        $this->jsonSerializer = new JsonSerializer();
        $this->logger = $this->configuration->getLogger();
        $this->baseUri = $baseUri != null ? $baseUri : $this->configuration->getEnvironment()->getBaseUri();
    }

    /**
     * @param $path
     * @param SdkAuthorization $authorization
     * @return mixed
     * @throws CheckoutApiException
     */
    public function get($path, SdkAuthorization $authorization)
    {
        $response = $this->invoke("GET", $path, null, $authorization);
        return $this->getResponseContents($response);
    }

    /**
     * @param $path
     * @param mixed $body
     * @param SdkAuthorization $authorization
     * @param string|null $idempotencyKey
     * @return mixed
     * @throws CheckoutApiException
     */
    public function post($path, $body, SdkAuthorization $authorization, $idempotencyKey = null)
    {
        $response = $this->invoke("POST", $path, is_null($body) ? $body : $this->jsonSerializer->serialize($body), $authorization, $idempotencyKey);

        return $this->jsonSerializer->deserialize($response->getBody());
    }

    /**
     * @param $path
     * @param mixed $body
     * @param SdkAuthorization $authorization
     * @return mixed
     * @throws CheckoutApiException
     */
    public function put($path, $body, SdkAuthorization $authorization)
    {
        $response = $this->invoke("PUT", $path, $this->jsonSerializer->serialize($body), $authorization);
        return $this->jsonSerializer->deserialize($response->getBody());
    }

    /**
     * @param $path
     * @param mixed $body
     * @param SdkAuthorization $authorization
     * @return mixed
     * @throws CheckoutApiException
     */
    public function patch($path, $body, SdkAuthorization $authorization)
    {
        $response = $this->invoke("PATCH", $path, $this->jsonSerializer->serialize($body), $authorization);
        return $this->jsonSerializer->deserialize($response->getBody());
    }

    /**
     * @param $path
     * @param SdkAuthorization $authorization
     * @throws CheckoutApiException
     */
    public function delete($path, SdkAuthorization $authorization)
    {
        $this->invoke("DELETE", $path, null, $authorization);
    }

    /**
     * @param $path
     * @param AbstractQueryFilter $body
     * @param SdkAuthorization $authorization
     * @return mixed
     * @throws CheckoutApiException
     */
    public function query($path, AbstractQueryFilter $body, SdkAuthorization $authorization)
    {
        $this->logger->info("GET " . $path);
        $queryParameters = $body->getEncodedQueryParameters();
        if (!empty($queryParameters)) {
            $path .= "?" . $queryParameters;
        }
        $response = $this->invoke("GET", $path, null, $authorization);
        return $this->getResponseContents($response);
    }

    /**
     * @param $path
     * @param FileRequest $fileRequest
     * @param SdkAuthorization $authorization
     * @return mixed
     * @throws CheckoutApiException
     */
    public function submitFile($path, FileRequest $fileRequest, SdkAuthorization $authorization)
    {
        return $this->submit($path, $fileRequest, $authorization, "file");
    }

    /**
     * @param $path
     * @param FileRequest $fileRequest
     * @param SdkAuthorization $authorization
     * @return mixed
     * @throws CheckoutApiException
     */
    public function submitFileFilesApi($path, FileRequest $fileRequest, SdkAuthorization $authorization)
    {
        return $this->submit($path, $fileRequest, $authorization, "path");
    }

    /**
     * @param $path
     * @param FileRequest $fileRequest
     * @param SdkAuthorization $authorization
     * @param $multipart
     * @return mixed
     * @throws CheckoutApiException
     */
    private function submit($path, FileRequest $fileRequest, SdkAuthorization $authorization, $multipart)
    {
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
                ]]);
            return json_decode($response->getBody(), true);
        } catch (Exception $e) {
            $this->logger->error($path . " error: " . $e->getMessage());
            if ($e instanceof RequestException) {
                throw CheckoutApiException::from($e);
            }
            throw new CheckoutApiException($e);
        }
    }

    /**
     * @param $method
     * @param $path
     * @param string|null $body
     * @param SdkAuthorization $authorization
     * @param string|null $idempotencyKey
     * @return ResponseInterface
     * @throws CheckoutApiException
     */
    private function invoke($method, $path, $body, SdkAuthorization $authorization, $idempotencyKey = null)
    {
        try {
            $this->logger->info($method . " " . $path);
            $headers = $this->getHeaders($authorization, "application/json", $idempotencyKey);
            return $this->client->request($method, $this->getRequestUrl($path), [
                "verify" => false,
                "body" => $body,
                "headers" => $headers
            ]);
        } catch (Exception $e) {
            $this->logger->error($path . " error: " . $e->getMessage());
            if ($e instanceof RequestException) {
                throw CheckoutApiException::from($e);
            }
            throw new CheckoutApiException($e);
        }
    }

    /**
     * @param string $path
     * @return string
     */
    private function getRequestUrl($path)
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
    private function getHeaders(SdkAuthorization $authorization, $contentType, $idempotencyKey)
    {
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
     * @param $response
     * @return mixed
     */
    private function getResponseContents($response)
    {
        $contentType = $response->getHeader("Content-Type");
        if (in_array("text/csv", $contentType)) {
            return $response->getBody()->getContents();
        }
        return $this->jsonSerializer->deserialize($response->getBody());
    }
}
