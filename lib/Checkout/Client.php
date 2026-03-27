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
     * Convert request object to form-urlencoded string
     *
     * @param $request
     * @return string
     */
    protected function createFormUrlEncodedContent($request)
    {
        $data = [];
        
        // Convert object properties to snake_case form data
        $reflection = new \ReflectionClass($request);
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);
        
        foreach ($properties as $property) {
            $value = $property->getValue($request);
            if ($value !== null) {
                // Convert camelCase to snake_case
                $fieldName = $this->camelToSnakeCase($property->getName());
                
                // Handle boolean values (convert to lowercase string)
                if (is_bool($value)) {
                    $stringValue = $value ? 'true' : 'false';
                } else {
                    $stringValue = (string) $value;
                }
                
                $data[$fieldName] = $stringValue;
            }
        }
        
        return http_build_query($data);
    }

    /**
     * Convert camelCase to snake_case
     *
     * @param string $input
     * @return string
     */
    protected function camelToSnakeCase($input)
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $input));
    }
}
