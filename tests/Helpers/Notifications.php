<?php

namespace Checkout\Tests\Helpers;

class Notifications
{
    public static function generateID()
    {
        return 'ntf_' . substr(md5(rand()), 0, 26);
    }
}
