<?php

namespace Checkout\tests\Library;

use Checkout\Library\Exceptions\CheckoutHttpException;
use PHPUnit\Framework\TestCase;

class CheckoutHttpExceptionTest extends TestCase
{
    public function testBody()
    {
        $exception = new CheckoutHttpException('RUN TEST', 1000);
        $body = '{body}';
        $exception->setBody($body);
        $this->assertEquals($body, $exception->getBody());
    }

    public function testErrors()
    {
        $exception = new CheckoutHttpException('RUN TEST', 1000);
        $body = '{"error_codes": ["error1","error2","error3"]}';
        $errors = array("error1","error2","error3");
        $exception->setBody($body);
        $this->assertEquals($errors, $exception->getErrors());
    }
}
