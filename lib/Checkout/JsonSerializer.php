<?php

namespace Checkout;

use DateTime;

class JsonSerializer
{

    const KEYS_TRANSFORMATIONS = array(
        "three_ds" => "3ds",
        "if_match" => "if-match"
    );

    /**
     * @param mixed $body
     * @return string
     */
    public function serialize($body)
    {
        return json_encode($this->normalize(is_array($body) ? $body : get_object_vars($body)));
    }

    private function normalize(array $props)
    {
        $array = array_filter($props, function ($value) {
            return !is_null($value);
        });
        foreach ($array as $key => $value) {
            // customization
            if ($value instanceof DateTime) {
                $array[$key] = CheckoutUtils::formatDate($value);
            } elseif (is_object($value)) {
                $array[$key] = $this->normalize(get_object_vars($value));
            }
            $array = $this->applyKeysTransformations($array, $key);
        }
        return $array;
    }

    private function applyKeysTransformations(array $arr, $key)
    {
        foreach (self::KEYS_TRANSFORMATIONS as $originalKey => $modifiedKey) {
            if ($key == $originalKey && array_key_exists($originalKey, $arr)) {
                $keys = array_keys($arr);
                $keys[array_search($originalKey, $keys)] = $modifiedKey;
                $arr = array_combine($keys, $arr);
            }
        }
        return $arr;
    }

    /**
     * @param mixed $object
     * @return mixed
     */
    public function deserialize($object)
    {
        return json_decode($object, true);
    }

}
