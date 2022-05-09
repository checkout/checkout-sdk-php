<?php

namespace Checkout;

use GuzzleHttp\Exception\RequestException;

class CheckoutApiException extends CheckoutException
{

    /**
     * @deprecated Since version 2.3.0. Please get this information from $http_metadata headers or $error_details.
     */
    public $request_id;
    /**
     * @deprecated Since version 2.3.0. Please use $http_metadata.
     */
    public $http_status_code;
    /**
     * @var array
     */
    public $error_details;
    /**
     * @var HttpMetadata
     */
    public $http_metadata;

    /**
     * @param RequestException $requestException
     * @return CheckoutApiException
     */
    public static function from(RequestException $requestException)
    {
        $body = json_decode($requestException->getResponse()->getBody()->getContents(), true);
        $ex = new CheckoutApiException(sprintf(
            "The API response status code (%s) does not indicate success.",
            $requestException->getCode()
        ));
        $ex->error_details = $body;
        $ex->http_metadata = CheckoutUtils::getHttpMetadata($requestException->getResponse());
        $ex->request_id = isset($body["request_id"]) ? $body["request_id"] : null;
        $ex->http_status_code = $requestException->getCode();
        return $ex;
    }
}
