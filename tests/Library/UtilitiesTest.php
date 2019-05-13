<?php

namespace Checkout\tests\Library;

use Checkout\Library\Utilities;
use PHPUnit\Framework\TestCase;

/**
 * Fully tested
 */
class UtilitiesTest extends TestCase
{

    /**
     * @param        array $arr
     * @dataProvider providerArray
     */
    public function testGetValueFromArray(array $arr)
    {
        if (sizeof($arr)) {
            foreach ($arr as $key => $value) {
                $element = Utilities::getValueFromArray($arr, $key); // Get element from array
                $this->assertEquals($arr[$key], $element); // Verify if value was not modified
            }
        } else {
            $element = Utilities::getValueFromArray($arr); // Get element from array
            $this->assertNull($element);
        }
    }

    public function providerArray()
    {
        return array(
            array(array()),
            array(array('A', 'B', 'C')),
            array(array('a' => 'A', 'b' => 'B')),
            array(array(99 => 'A', 98 => 'B'))
        );
    }

    /**
     * @param        mixed $value
     * @param        bool  $empty
     * @dataProvider providerEmpties
     */
    public function testIsEmpty($value, $empty)
    {
        $result = Utilities::isEmpty($value);
        $this->assertEquals($result, $empty);
    }

    public function providerEmpties()
    {
        return array(

            array('', true),
            array(null, true),
            array(array(), true),

            array('a', false),
            array(1, false),
            array(array('a'), false)

        );
    }

    /**
     * @param        mixed $value
     * @dataProvider provideToArray
     */
    public function testToArray($value)
    {
        $result = Utilities::toArray($value); // Turn into array
        $this->assertTrue(is_array($result)); // Verify if returned value is array
    }

    public function provideToArray()
    {
        return array(
            array(array()),
            array(1),
            array('a')
        );
    }

    /**
     * @param        array $arr
     * @dataProvider providerArray
     */
    public function testGetFirstElementPointer(array $arr)
    {
        if (sizeof($arr)) {
            $pointer = Utilities::getFirstElementPointer($arr);
            $first = array_shift($arr);
            $this->assertEquals($first, $pointer);
        } else {
            $element = Utilities::getValueFromArray($arr); // Get element from array
            $this->assertNull($element);
        }
    }

    /**
     * @param        array $arr
     * @dataProvider providerArray
     */
    public function testGetLastElementPointer(array $arr)
    {
        if (sizeof($arr)) {
            $pointer = Utilities::getLastElementPointer($arr);
            $last = array_pop($arr);
            $this->assertEquals($last, $pointer);
        } else {
            $element = Utilities::getValueFromArray($arr); // Get element from array
            $this->assertNull($element);
        }
    }

    public function testLoadConfig()
    {
        $path = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'config.ini';
        $result = Utilities::loadConfig($path);
        $this->assertTrue(is_array($result));
    }

    /**
     * @param        string $str
     * @param        string $result
     * @dataProvider providerToCamelCase
     */
    public function testToCamelCase($str, $result)
    {
        $camel = Utilities::toCamelCase($str);
        $this->assertEquals($result, $camel, $camel);
    }

    public function providerToCamelCase()
    {
        return array(
            array('provider to camel case', 'ProviderToCamelCase'),
            array('test to camel', 'TestToCamel'),
            array('camel case', 'CamelCase')
        );
    }
}
