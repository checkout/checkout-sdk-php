<?php

namespace Checkout;

use GuzzleHttp\Exception\RequestException;

class CheckoutApiException extends CheckoutException
{

    public $request_id;
    public $http_status_code;
    public $error_details;

    public function __construct($message)
    {
        parent::__construct($message);
    }

    public static function from(RequestException $requestException)
    {
        $body = json_decode($requestException->getResponse()->getBody()->getContents(), true);
        $ex = new CheckoutApiException("The API response status code (" . $requestException->getCode() . ") does not indicate success.");
        $ex->request_id = isset($body["request_id"]) ? $body["request_id"] : null;
        $ex->http_status_code = $requestException->getCode();
        $ex->error_details = $body;
        return $ex;
    }

}
