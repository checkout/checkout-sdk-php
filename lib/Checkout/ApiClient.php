<?php

namespace Checkout;

use Checkout\Common\AbstractQueryFilter;
use Checkout\Files\FileRequest;
use Common\TelemetryQueue;
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

    private $enableTelemetry;

    private $requestMetricsQueue;

    public function __construct(CheckoutConfiguration $configuration, $baseUri = null)
    {
        $this->configuration = $configuration;
        $this->client = $configuration->getHttpClientBuilder()->getClient();
        $this->jsonSerializer = new JsonSerializer();
        $this->logger = $this->configuration->getLogger();
        $this->baseUri = $baseUri != null ? $baseUri : $this->configuration->getEnvironment()->getBaseUri();
        $this->enableTelemetry = $this->configuration->isEnableTelemetry();
        $this->requestMetricsQueue = new TelemetryQueue();
    }

    /**
     * @param $path
     * @param SdkAuthorization $authorization
     * @return array
     * @throws CheckoutApiException
     */
    public function get($path, SdkAuthorization $authorization)
    {
        return $this->invoke("GET", $path, null, $authorization);
    }

    /**
     * @param $path
     * @param mixed $body
     * @param SdkAuthorization $authorization
     * @param string|null $idempotencyKey
     * @return array
     * @throws CheckoutApiException
     */
    public function post($path, $body, SdkAuthorization $authorization, $idempotencyKey = null)
    {
        return $this->invoke("POST", $path, is_null($body)
            ? $body
            : $this->jsonSerializer->serialize($body), $authorization, $idempotencyKey);
    }

    /**
     * @param $path
     * @param mixed $body
     * @param SdkAuthorization $authorization
     * @return array
     * @throws CheckoutApiException
     */
    public function put($path, $body, SdkAuthorization $authorization)
    {
        return $this->invoke("PUT", $path, $this->jsonSerializer->serialize($body), $authorization);
    }

    /**
     * @param $path
     * @param mixed $body
     * @param SdkAuthorization $authorization
     * @return array
     * @throws CheckoutApiException
     */
    public function patch($path, $body, SdkAuthorization $authorization)
    {
        return $this->invoke("PATCH", $path, $this->jsonSerializer->serialize($body), $authorization);
    }

    /**
     * @param $path
     * @param SdkAuthorization $authorization
     * @return array
     * @throws CheckoutApiException
     */
    public function delete($path, SdkAuthorization $authorization)
    {
        return $this->invoke("DELETE", $path, null, $authorization);
    }

    /**
     * @param $path
     * @param AbstractQueryFilter $body
     * @param SdkAuthorization $authorization
     * @return array
     * @throws CheckoutApiException
     */
    public function query($path, AbstractQueryFilter $body, SdkAuthorization $authorization)
    {
        $this->logger->info("GET " . $path);
        $queryParameters = $body->getEncodedQueryParameters();
        if (!empty($queryParameters)) {
            $path .= "?" . $queryParameters;
        }
        return $this->invoke("GET", $path, null, $authorization);
    }

    /**
     * @param $path
     * @param FileRequest $fileRequest
     * @param SdkAuthorization $authorization
     * @return array
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
     * @return array
     * @throws CheckoutApiException
     */
    public function submitFileFilesApi($path, FileRequest $fileRequest, SdkAuthorization $authorization)
    {
        return $this->submit($path, $fileRequest, $authorization, "path");
    }

    private function handleRequest($method, $path, SdkAuthorization $authorization, array $requestOptions)
    {
        try {
            $headers = $requestOptions['headers'];

            if ($this->enableTelemetry) {
                $currentRequestId = uniqid();
                $lastRequestMetric = $this->requestMetricsQueue->dequeue();
                
                if ($lastRequestMetric !== null) {
                    $lastRequestMetric->requestId = $currentRequestId;
                    $headers["Cko-Sdk-Telemetry"] = base64_encode(json_encode([
                        'prev-request-id' => $lastRequestMetric->prevRequestId,
                        'prev-request-duration' => $lastRequestMetric->prevRequestDuration
                    ]));
                }
                
                $startTime = microtime(true);
                $response = $this->client->request($method, $this->getRequestUrl($path), array_merge(
                    $requestOptions,
                    ['headers' => $headers]
                ));
                $duration = (int)((microtime(true) - $startTime) * 1000);
                
                $lastRequestMetric->prevRequestDuration = $duration;
                $lastRequestMetric->prevRequestId = $currentRequestId;
                $this->requestMetricsQueue->enqueue($lastRequestMetric);
            } else {
                $response = $this->client->request($method, $this->getRequestUrl($path), array_merge(
                    $requestOptions,
                    ['headers' => $headers]
                ));
            }
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
     * @param $path
     * @param FileRequest $fileRequest
     * @param SdkAuthorization $authorization
     * @param $multipart
     * @return array
     * @throws CheckoutApiException
     */
    private function submit($path, FileRequest $fileRequest, SdkAuthorization $authorization, $multipart)
    {
    // Keep specific logging in submit
        $this->logger->info("POST " . $path . " file: " . $fileRequest->file);
    
        $headers = $this->getHeaders($authorization, null, null, null);
        $requestOptions = [
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
        ];
    
        return $this->handleRequest("POST", $path, $authorization, $requestOptions);
    }

    /**
     * @param string $method
     * @param string $path
     * @param $body
     * @param SdkAuthorization $authorization
     * @param null $idempotencyKey
     * @return array
     * @throws CheckoutApiException
     */
    private function invoke($method, $path, $body, SdkAuthorization $authorization, $idempotencyKey = null)
    {
        // Keep specific logging in invoke
        $this->logger->info($method . " " . $path);
        
        $headers = $this->getHeaders($authorization, "application/json", $idempotencyKey, null);
        $requestOptions = [
            "verify" => false,
            "body" => $body,
            "headers" => $headers
        ];
        
        return $this->handleRequest($method, $path, $authorization, $requestOptions);
    }

    // /**
    //  * @param $path
    //  * @param FileRequest $fileRequest
    //  * @param SdkAuthorization $authorization
    //  * @param $multipart
    //  * @return array
    //  * @throws CheckoutApiException
    //  */
    // private function submit($path, FileRequest $fileRequest, SdkAuthorization $authorization, $multipart)
    // {
    //     try {
    //         $this->logger->info("POST " . $path . " file: " . $fileRequest->file);
    //         $headers = $this->getHeaders($authorization, null, null);
    //         if ($this->enableTelemetry) {
    //             $currentRequestId = uniqid(); //TODO: look at how uuid is made in go and copy
    //             $lastRequestMetric = $this->requestMetricsQueue->dequeue();

    //             if ($lastRequestMetric !== null) {
    //                 $lastRequestMetric->requestId = $currentRequestId;
    //                 $headers[self::CKO_TELEMETRY_HEADER] = base64_encode(json_encode([
    //                     'prev-request-id' => $lastRequestMetric->prevRequestId,
    //                     'prev-request-duration' => $lastRequestMetric->prevRequestDuration
    //                 ]));
    //             }

    //             $startTime = microtime(true);
    //             $response = $this->client->request("POST", $this->getRequestUrl($path), [
    //                 "verify" => false,
    //                 "headers" => $headers,
    //                 "multipart" => [
    //                     [
    //                         "name" => $multipart,
    //                         "contents" => fopen($fileRequest->file, "r")
    //                     ],
    //                     [
    //                         "name" => "purpose",
    //                         "contents" => $fileRequest->purpose
    //                     ]
    //                 ]]);

    //             $duration = (int)((microtime(true) - $startTime) * 1000);

    //             $lastRequestMetric->prevRequestDuration = $duration;
    //             $lastRequestMetric->prevRequestId = $currentRequestId;
    //             $this->requestMetricsQueue->enqueue($lastRequestMetric);
    //         } else {
    //             $response = $this->client->request("POST", $this->getRequestUrl($path), [
    //                 "verify" => false,
    //                 "headers" => $headers,
    //                 "multipart" => [
    //                     [
    //                         "name" => $multipart,
    //                         "contents" => fopen($fileRequest->file, "r")
    //                     ],
    //                     [
    //                         "name" => "purpose",
    //                         "contents" => $fileRequest->purpose
    //                     ]
    //                 ]]);
    //         }
    //         return $this->getResponseContents($response);
    //     } catch (Exception $e) {
    //         $this->logger->error($path . " error: " . $e->getMessage());
    //         if ($e instanceof RequestException) {
    //             throw CheckoutApiException::from($e);
    //         }
    //         throw new CheckoutApiException($e);
    //     }
    // }

    // private function invoke($method, $path, $body, SdkAuthorization $authorization, $idempotencyKey = null)
    // {
    //     try {
    //         $this->logger->info($method . " " . $path);
    //         $headers = $this->getHeaders($authorization, "application/json", $idempotencyKey);
    //         if ($this->enableTelemetry) {
    //             // Generate current request ID and handle telemetry only if enabled
    //             $currentRequestId = uniqid();
    //             $lastRequestMetric = $this->requestMetricsQueue->dequeue();
                
    //             if ($lastRequestMetric !== null) {
    //                 $lastRequestMetric->requestId = $currentRequestId;
    //                 $headers[self::CKO_TELEMETRY_HEADER] = base64_encode(json_encode([
    //                     'prev-request-id' => $lastRequestMetric->prevRequestId,
    //                     'prev-request-duration' => $lastRequestMetric->prevRequestDuration
    //                 ]));
    //             }
                
    //             $startTime = microtime(true);
    //             $response = $this->client->request($method, $this->getRequestUrl($path), [
    //                 "verify" => false,
    //                 "body" => $body,
    //                 "headers" => $headers
    //             ]);
    //             $duration = (int)((microtime(true) - $startTime) * 1000);
                
    //             $lastRequestMetric->prevRequestDuration = $duration;
    //             $lastRequestMetric->prevRequestId = $currentRequestId;
    //             $this->requestMetricsQueue->enqueue($lastRequestMetric);
    //         } else {
    //             $response = $this->client->request($method, $this->getRequestUrl($path), [
    //                 "verify" => false,
    //                 "body" => $body,
    //                 "headers" => $headers
    //             ]);
    //         }
    //         return $this->getResponseContents($response);
    //     } catch (Exception $e) {
    //         $this->logger->error($path . " error: " . $e->getMessage());
    //         if ($e instanceof RequestException) {
    //             throw CheckoutApiException::from($e);
    //         }
    //         throw new CheckoutApiException($e);
    //     }
    // }

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
     * @param string|null $telemetryData
     * @return array
     * @throws CheckoutAuthorizationException
     */
    private function getHeaders(SdkAuthorization $authorization, $contentType, $idempotencyKey, $telemetryData)
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
        if (!empty($telemetryData)) {
            $headers["Cko-Sdk-Telemetry"] = base64_encode(json_encode($telemetryData));
        }
        return $headers;
    }

    /**
     * @param $response
     * @return array
     */
    private function getResponseContents($response)
    {
        $contentType = $response->getHeader("Content-Type");
        if (in_array("text/csv", $contentType)) {
            return $this->createResponse($response, $response->getBody()->getContents());
        }
        return $this->createResponse($response, $this->jsonSerializer->deserialize($response->getBody()));
    }

    /**
     * @param Response|null $http_response
     * @param string|array $data
     * @return array
     */
    private static function createResponse($http_response = null, $data = null)
    {
        $response = array();
        if ($http_response != null) {
            $response["http_metadata"] = CheckoutUtils::getHttpMetadata($http_response);
        }
        if ($data != null) {
            if (is_array($data)) {
                //Validate if array is sequential, basically list contents
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
