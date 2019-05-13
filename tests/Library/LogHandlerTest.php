<?php

namespace Checkout\tests\Library;

use Checkout\Library\LogHandler;
use PHPUnit\Framework\TestCase;

class LogHandlerTest extends TestCase
{
    public function testConstructor()
    {
        $this->assertInstanceOf(LogHandler::class, new LogHandler());
    }

    public function testRequest()
    {
        LogHandler::request('RUN TEST');
        $this->assertTrue(true);
    }

    public function testResponse()
    {
        LogHandler::response('RUN TEST');
        $this->assertTrue(true);
    }
}
