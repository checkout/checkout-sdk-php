<?php

namespace Checkout\tests\Helpers;

use Checkout\Library\HttpHandler;

class HttpHandlers
{
    public static function generateURL()
    {
        return 'www.' . substr(md5(rand()), 0, 20) . '.com';
    }

    public static function generateHeader()
    {
        return 'Header: ' . md5(rand());
    }

    public static function generateHandler()
    {
        return HttpHandler::create(static::generateURL())->setConfiguration(CheckoutConfigurations::generateModel());
    }

    public static function generateUUID()
    {
        // Snippet from: http://www.php.net/manual/en/function.uniqid.php#94959
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                       mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
                       mt_rand(0, 0xffff),
                       mt_rand(0, 0x0fff) | 0x4000,
                       mt_rand(0, 0x3fff) | 0x8000,
                       mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }
}
