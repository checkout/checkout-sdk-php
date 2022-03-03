<?php

namespace Checkout;

use DateTime;

class JsonSerializer
{

    private const KEYS_TRANSFORMATIONS = array(
        "three_ds" => "3ds"
    );

    /**
     * @param mixed $body
     * @return string
     */
    public function serialize($body): string
    {
        return json_encode($this->normalize(get_object_vars($body)));
    }

    private function normalize(array $arr): array
    {
        foreach ($arr as $key => $value) {
            // customization
            if ($value instanceof DateTime) {
                $arr[$key] = CheckoutUtils::formatDate($value);
            } else if (is_object($value)) {
                $arr[$key] = $this->normalize(get_object_vars($value));
            }
            $arr = $this->applyKeysTransformations($arr, $key);
        }
        return $arr;
    }

    private function applyKeysTransformations(array $arr, string $key): array
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
