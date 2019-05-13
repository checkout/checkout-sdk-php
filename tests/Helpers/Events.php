<?php

namespace Checkout\tests\Helpers;

use Checkout\Models\Events\Event;

class Events
{
    public static function generateID()
    {
        return 'evt_' . substr(md5(rand()), 0, 26);
    }

    public static function generateModel()
    {
        $event = new Event(static::generateID());
        $event->type = 'TEST_EVENT';
        $event->version = '2.0';

        return $event;
    }
}
