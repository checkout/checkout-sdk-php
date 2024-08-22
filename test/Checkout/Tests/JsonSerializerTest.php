<?php

namespace Checkout\Tests;

use Checkout\CheckoutUtils;
use Checkout\JsonSerializer;
use DateTime;
use PHPUnit\Framework\TestCase;

class JsonSerializerTest extends TestCase
{
    public function testSerializeWithSimpleArray()
    {
        $serializer = new JsonSerializer();
        $input = [
            'name' => 'John Doe',
            'age' => 30,
            'null_value' => null
        ];
        $expected = json_encode([
            'name' => 'John Doe',
            'age' => 30
        ]);

        $this->assertEquals($expected, $serializer->serialize($input));
    }

    public function testSerializeWithDateTime()
    {
        $serializer = new JsonSerializer();
        $date = new DateTime('2023-08-22');
        $input = [
            'name' => 'John Doe',
            'created_at' => $date
        ];
        $expected = json_encode([
            'name' => 'John Doe',
            'created_at' => CheckoutUtils::formatDate($date)
        ]);

        $this->assertEquals($expected, $serializer->serialize($input));
    }

    public function testSerializeWithNestedArray()
    {
        $serializer = new JsonSerializer();
        $input = [
            'user' => [
                'name' => 'John Doe',
                'address' => [
                    'city' => 'New York',
                    'zip' => '10001',
                    'null_value' => null
                ]
            ]
        ];
        $expected = json_encode([
            'user' => [
                'name' => 'John Doe',
                'address' => [
                    'city' => 'New York',
                    'zip' => '10001'
                ]
            ]
        ]);

        $this->assertEquals($expected, $serializer->serialize($input));
    }

    public function testSerializeWithObject()
    {
        $serializer = new JsonSerializer();
        $input = (object)[
            'name' => 'John Doe',
            'age' => 30
        ];
        $expected = json_encode([
            'name' => 'John Doe',
            'age' => 30
        ]);

        $this->assertEquals($expected, $serializer->serialize($input));
    }

    public function testSerializeWithKeysTransformation()
    {
        $serializer = new JsonSerializer();
        $input = [
            'three_ds' => 'enabled',
            'if_match' => 'etag_value'
        ];
        $expected = json_encode([
            '3ds' => 'enabled',
            'if-match' => 'etag_value'
        ]);

        $this->assertEquals($expected, $serializer->serialize($input));
    }

    public function testSerializeWithIterableObject()
    {
        $serializer = new JsonSerializer();

        $iterableObject = new IterableObject([
            'item1' => 'value1',
            'item2' => 'value2'
        ]);

        $input = [
            'iterable' => $iterableObject
        ];

        if (PHP_VERSION_ID >= 70100) {
            // PHP >= 8.0
            $expected = json_encode([
                'iterable' => [
                    'item1' => 'value1',
                    'item2' => 'value2'
                ]
            ]);
        } else {
            // PHP 5.6 - 7.x
            $expected = json_encode([
                'iterable' => [
                    'items' => [
                        'item1' => 'value1',
                        'item2' => 'value2'
                    ]
                ]
            ]);
        }

        $this->assertEquals($expected, $serializer->serialize($input));
    }
}
