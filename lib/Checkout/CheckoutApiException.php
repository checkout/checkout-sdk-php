<?php

namespace Checkout;

use GuzzleHttp\Exception\RequestException;

class CheckoutApiException extends CheckoutException
{

    public ?string $request_id;
    public int $http_status_code;
    public ?array $error_details;

    public function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function from(RequestException $requestException): CheckoutApiException
    {
        $body = json_decode($requestException->getResponse()->getBody()->getContents(), true);
        $ex = new CheckoutApiException("The API response status code (" . $requestException->getCode() . ") does not indicate success.");
        $ex->request_id = $body != null ? $body["request_id"] : null;
        $ex->http_status_code = $requestException->getCode();
        $ex->error_details = $body;
        return $ex;
    }

}
