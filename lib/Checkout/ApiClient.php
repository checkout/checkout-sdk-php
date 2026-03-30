<?php

namespace Checkout;

use Checkout\Common\AbstractQueryFilter;
use Checkout\Files\FileRequest;
use Exception;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Stream;
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
        $requestBody,
        SdkAuthorization $authorization,
        ?string $idempotencyKey = null
    ): array {
        return $this->invoke("POST", $path, $requestBody, $authorization, $idempotencyKey);
    }

    /**
     * @param string $path
     * @param mixed $requestBody
     * @param SdkAuthorization $authorization
     * @param mixed $headers
     * @return array
     * @throws CheckoutApiException
     */
    public function put(string $path, $requestBody, SdkAuthorization $authorization, $headers = null): array
    {
        return $this->invoke("PUT", $path, $requestBody, $authorization, null, $headers);
    }

    /**
     * @param string $path
     * @param mixed $requestBody
     * @param SdkAuthorization $authorization
     * @return array
     * @throws CheckoutApiException
     */
    public function patch(string $path, $requestBody, SdkAuthorization $authorization): array
    {
        return $this->invoke("PATCH", $path, $requestBody, $authorization);
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
     * @param AbstractQueryFilter $requestBody
     * @param SdkAuthorization $authorization
     * @return array
     * @throws CheckoutApiException
     */
    public function query(
        string $path,
        AbstractQueryFilter $requestBody,
        SdkAuthorization $authorization
    ): array {
        $this->logger->info("GET " . $path);
        $queryParameters = $requestBody->getEncodedQueryParameters();
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

            // Provide a stream with explicit size so Guzzle can set Content-Length correctly.
            // Without this, stream->getSize() may return null (e.g. some stream wrappers),
            // causing Guzzle to use Transfer-Encoding: chunked, which many upload APIs reject.
            $filePath = $fileRequest->file;
            $fileSize = filesize($filePath);
            $fileHandle = fopen($filePath, "r");
            $fileStream = new Stream($fileHandle, ["size" => $fileSize]);

            $response = $this->client->request("POST", $this->getRequestUrl($path), [
                "verify" => false,
                "headers" => $headers,
                "multipart" => [
                    [
                        "name" => $multipart,
                        "contents" => $fileStream,
                        "filename" => basename($filePath)
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
     * @param mixed $headers
     * @return array
     * @throws CheckoutApiException
     */
    private function invoke(
        string $method,
        string $path,
        $requestBody,
        SdkAuthorization $authorization,
        ?string $idempotencyKey = null,
        $headers = null
    ): array {
        try {
            $this->logger->info($method . " " . $path);

            // Determine body and content type for the request depending on the incoming requestBody
            if ($requestBody !== null) {
                if (is_array($requestBody) && $this->isMultipartFormData($requestBody)) {
                    // Handle multipart form data content
                    $body = $requestBody;
                    $contentType = null; // Let Guzzle set multipart content type
                } elseif (is_string($requestBody) && $this->isFormUrlEncodedContent($requestBody)) {
                    // Handle form URL encoded content
                    $body = $requestBody;
                    $contentType = "application/x-www-form-urlencoded";
                } else {
                    // Default: JSON call, serialize body to JSON
                    $body = $this->jsonSerializer->serialize($requestBody);
                    $contentType = "application/json";
                }
            } else {
                // Empty default
                $body = null;
                $contentType = "application/json";
            }

            // Build up the headers with correct content type
            $requestHeaders = $this->getHeaders($authorization, $contentType, $idempotencyKey, $headers);

            // Make the call
            $response = $this->client->request($method, $this->getRequestUrl($path), [
                "verify" => false,
                "body" => $body,
                "headers" => $requestHeaders
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
     * @param mixed $customHeaders
     * @return array
     * @throws CheckoutAuthorizationException
     */
    private function getHeaders(
        SdkAuthorization $authorization,
        ?string $contentType,
        ?string $idempotencyKey,
        $customHeaders = null
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
        
        // Add custom headers using reflection
        if ($customHeaders !== null) {
            $reflection = new \ReflectionClass($customHeaders);
            foreach ($reflection->getProperties() as $property) {
                $property->setAccessible(true);
                $value = $property->getValue($customHeaders);
                if ($value !== null && $value !== '') {
                    // Convert property name to HTTP header format
                    $headerName = $this->convertPropertyToHeader($property->getName());
                    $headers[$headerName] = (string)$value;
                }
            }
        }
        
        return $headers;
    }

    /**
     * Convert PHP property name to HTTP header format
     * @param string $propertyName
     * @return string
     */
    private function convertPropertyToHeader(string $propertyName): string
    {
        // Convert snake_case to Pascal-Case for HTTP headers
        $parts = explode('_', $propertyName);
        return implode('-', array_map('ucfirst', $parts));
    }

    /**
     * Check if request body represents multipart form data
     * @param mixed $requestBody
     * @return bool
     */
    private function isMultipartFormData($requestBody): bool
    {
        // Check if it's an array with multipart structure (name, contents, filename)
        if (is_array($requestBody)) {
            foreach ($requestBody as $item) {
                if (is_array($item) && isset($item['name']) && isset($item['contents'])) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Check if request body is form URL encoded content
     * @param mixed $requestBody
     * @return bool
     */
    private function isFormUrlEncodedContent($requestBody): bool
    {
        // Check if it's a string that looks like URL encoded form data (key=value&key2=value2)
        if (is_string($requestBody)) {
            return preg_match('/^[^=]+=[^&]*(&[^=]+=[^&]*)*$/', $requestBody) === 1;
        }
        return false;
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
