<?php

namespace Checkout;

abstract class Client
{
    protected $apiClient;

    protected $configuration;

    private $sdkAuthorizationType;

    /**
     * @param ApiClient $apiClient
     * @param CheckoutConfiguration $configuration
     * @param $sdkAuthorizationType
     */
    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration, $sdkAuthorizationType)
    {
        $this->apiClient = $apiClient;
        $this->configuration = $configuration;
        $this->sdkAuthorizationType = $sdkAuthorizationType;
    }

    /**
     * @return mixed
     */
    protected function sdkAuthorization()
    {
        return $this->configuration->getSdkCredentials()->getAuthorization($this->sdkAuthorizationType);
    }

    /**
     * @param $authorizationType
     * @return mixed
     */
    protected function sdkSpecificAuthorization($authorizationType)
    {
        return $this->configuration->getSdkCredentials()->getAuthorization($authorizationType);
    }

    /**
     * @param mixed ...$parts
     * @return string
     */
    protected function buildPath(...$parts)
    {
        return join("/", $parts);
    }

    /**
     * Encodes a caller-supplied value (an id or a name) as a single path segment for buildPath.
     * Path constants are passed to buildPath unchanged.
     *
     * @param string|int $segment the value to place in the path
     * @return string the value percent-encoded as defined in RFC 3986
     * @throws CheckoutArgumentException if the value is not a string or an integer, is empty, "." or "..",
     *     is not valid UTF-8, or contains "/", "\\" or an ASCII control character
     */
    protected function encodePathSegment($segment)
    {
        if (is_int($segment)) {
            $segment = (string)$segment;
        }
        if (!is_string($segment) || $segment === "" || $segment === "." || $segment === ".."
            || preg_match('//u', $segment) !== 1
            || preg_match('/[\/\\\\\x00-\x1F\x7F]/', $segment) === 1
        ) {
            throw new CheckoutArgumentException("invalid path segment");
        }
        return rawurlencode($segment);
    }
}
