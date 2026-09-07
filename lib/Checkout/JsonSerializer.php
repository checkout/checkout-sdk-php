<?php

namespace Checkout;

use Checkout\Common\DateOnly;
use DateTime;
use ReflectionClass;

class JsonSerializer
{

    const KEYS_TRANSFORMATIONS = array(
        "three_ds" => "3ds",
        "if_match" => "if-match"
    );

    /**
     * Per-class map of property name => whether it is annotated #[DateOnly]. Populated by
     * reflection on first use of each class.
     *
     * @var array<string, array<string, bool>>
     */
    private static $dateOnlyCache = array();

    /**
     * @param mixed $body
     * @return string
     */
    public function serialize($body)
    {
        return json_encode(
            is_array($body)
                ? $this->normalize($body, null)
                : $this->normalize(get_object_vars($body), $body),
            JSON_UNESCAPED_SLASHES
        );
    }

    /**
     * @param array $props properties to normalize
     * @param object|null $owner the object $props came from, or null when normalizing a raw
     *                           array. Needed to resolve #[DateOnly] by reflection.
     * @return array
     */
    private function normalize(array $props, $owner = null)
    {
        $array = array_filter($props, function ($value) {
            return !is_null($value);
        });
        foreach ($array as $key => $value) {
            if ($value instanceof DateTime) {
                $array[$key] = $this->isDateOnly($owner, $key)
                    ? CheckoutUtils::formatDateOnly($value)
                    : CheckoutUtils::formatDate($value);
            } elseif (is_array($value)) {
                $array[$key] = $this->normalize($value, null);
            } elseif (is_object($value)) {
                if (PHP_VERSION_ID >= 70100 && is_iterable($value)) {
                    $array[$key] = $this->normalize(iterator_to_array($value), null);
                } else {
                    $array[$key] = $this->normalize(get_object_vars($value), $value);
                }
            }
            $array = $this->applyKeysTransformations($array, $key);
        }
        return $array;
    }

    /**
     * Whether $property on $owner is annotated #[DateOnly], i.e. the swagger declares it
     * `format: date` and it must serialize as yyyy-MM-dd.
     *
     * Cached per class, so reflection runs once per class per process.
     *
     * @param object|null $owner
     * @param string $property
     * @return bool
     */
    private function isDateOnly($owner, $property)
    {
        if (!is_object($owner)) {
            return false;
        }
        $class = get_class($owner);
        if (!isset(self::$dateOnlyCache[$class])) {
            $map = array();
            foreach ((new ReflectionClass($class))->getProperties() as $reflected) {
                $map[$reflected->getName()] = count($reflected->getAttributes(DateOnly::class)) > 0;
            }
            self::$dateOnlyCache[$class] = $map;
        }
        return isset(self::$dateOnlyCache[$class][$property])
            && self::$dateOnlyCache[$class][$property];
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
