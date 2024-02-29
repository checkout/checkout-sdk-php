<?php

namespace Checkout;

use GuzzleHttp\Exception\RequestException;

class CheckoutApiException extends CheckoutException
{
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
        $response = $requestException->getResponse();

        $ex = new CheckoutApiException(sprintf(
            "The API response status code (%s) does not indicate success.",
            $requestException->getCode()
        ));
        $ex->error_details = isset($response)
            ? json_decode($response->getBody()->getContents(), true)
            : [];
        $ex->http_metadata = CheckoutUtils::getHttpMetadata($response);

        return $ex;
    }
}
