<?php

namespace Checkout\tests\Helpers;

use Checkout\Models\Webhooks\Webhook;

class Webhooks
{
    public static function generateID()
    {
        return 'wh_' . substr(md5(rand()), 0, 26);
    }

    public static function generateModel($id = '')
    {
        $webhook = new Webhook('www.checkout.com', $id);
        return $webhook;
    }
}
