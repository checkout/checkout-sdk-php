<?php

namespace Checkout\tests\Helpers;

use Checkout\Models\Response;

class Responses
{
    public static function generateID()
    {
        return 'tok_' . substr(md5(rand()), 0, 26);
    }

    public static function generateModelError()
    {
        $response = new Response();
        $response->http_code = 400;
        $response->error_codes = array('ERRORS');

        return $response;
    }

    public static function generateModelSuccess()
    {
        $response = new Response();
        $response->_links = array('TEST' => array('href' => HttpHandlers::generateURL()));

        return $response;
    }
}
