<?php

/**
 * Checkout.com
 * Authorised and regulated as an electronic money institution
 * by the UK Financial Conduct Authority (FCA) under number 900816.
 *
 * PHP version 7
 *
 * @category  SDK
 * @package   Checkout.com
 * @author    Platforms Development Team <platforms@checkout.com>
 * @copyright 2010-2019 Checkout.com
 * @license   https://opensource.org/licenses/mit-license.html MIT License
 * @link      https://docs.checkout.com/
 */

namespace Checkout\Library;

use Checkout\Library\HttpHandler;

/**
 * Parent class of Model.
 *
 * @category SDK
 * @package  Checkout.com
 * @author   Platforms Development Team <platforms@checkout.com>
 * @license  https://opensource.org/licenses/mit-license.html MIT License
 * @link     https://docs.checkout.com/
 */
abstract class Model
{

    /**
     * API Request URL.
     *
     * @var string
     */
    const MODEL_REQUEST_URL = '';
    
    /**
     * API Request banks URL.
     *
     * @var string
     */
    const MODEL_REQUEST_BANKS_URL = '';

    /**
     * API Request Method.
     *
     * @var string
     */
    const MODEL_REQUEST_METHOD = HttpHandler::METHOD_GET;

    /**
     * Aliases
     *
     * @var array
     */
    public static $aliases;


    /*
     * Response handlers
     */

    /**
     * Convert JSON response into a Model.
     *
     * @param  HttpHandler $http
     * @return Model
     */
    public static function load(HttpHandler $http)
    {
        $data = json_decode($http->getResponse(), true);

        if ($data === null) {
            $data = array();
        } else {

            // Fix aliases
            foreach (static::$aliases as $key => $value) {
                if (isset($data[$value])) {
                    $data[$key] = $data[$value];
                    unset($data[$value]);
                }
            }
        }

        $data['http_code'] = $http->getCode();
        return static::arrayToModel($data);
    }

    /**
     * Convert array into model. Second level properties will be array type.
     *
     * @param  array $arr
     * @return Model
     */
    protected static function arrayToModel(array $arr)
    {
        $obj = static::create($arr);
        foreach ($arr as $key => $value) {
            if (is_string($key)) {
                $obj->{$key} = $value;
            }
        }

        return $obj;
    }

    /**
     * Factory.
     *
     * @note   Some classes will have to override this function.
     * @param  array $response
     * @return Model
     */
    protected static function create(array $response)
    {
        return new static();
    }


    /**
     * Setters and Getters
     */

    /**
     * Get all field values.
     *
     * @return array $values
     */
    public function getValues()
    {
        $values = array();
        $arr = get_object_vars($this);
        unset($arr['http_code']);

        foreach ($arr as $key => &$value) {
            $alias = isset(static::$aliases[$key]) ? static::$aliases[$key] : $key;
            if ($value instanceof Model) {
                $value = $value->getValues();
            }
            $values[$alias] = $value;
        }

        return $values;
    }

    /**
     * Get a specific field.
     *
     * @param  mixed $key
     * @param  array  $values For recursion purposes.
     * @return mixed
     */
    public function getValue($key, array $values = array())
    {
        $arr = (array) $key;
        $index = array_shift($arr);
        
        if($values) {
            $value = isset($values[$index]) ? $values[$index] : $this->getValueAliased($index, $values);
        } else {
            $value = isset($this->{$index}) ? $this->{$index} : $this->getValueAliased($index);
        }

        if($arr) {
            $value = $this->getValue($arr, (array) $value);
        }

        return $value;
    }

    /**
     * Gets the value aliased.
     *
     * @param      string  $key     The key
     * @param      array   $values  The values
     *
     * @return     mixed  The value aliased.
     */
    protected function getValueAliased($key, array &$values = array())
    {
        $value = null;

        $index = array_search($key, static::$aliases);
        if($index !== false) {
            if($values && isset($values[$key])) {
                $value = $value[$key];
            } else if(isset($this->{$index})) {
                $value = $this->{$index};
            }
        }

        return $value;
    }

    /**
     * Get API URL for the model.
     *
     * @return string
     */
    public function getEndpoint()
    {
        $url = static::MODEL_REQUEST_URL;
        $arr = array();
        preg_match_all('/{(\w+)}/i', $url, $arr);

        if ($arr) {
            foreach ($arr[0] as $key => $value) {
                $url = str_replace($value, $this->getValue($arr[1][$key]), $url);
            }
        }

        return $url;
    }

    /**
     * Get ID if exists.
     *
     * @return string
     */
    public function getId()
    {
        return property_exists($this, 'id') ? $this->id : $this->getTokenId();
    }

    /**
     * Get action ID if exists.
     *
     * @return string
     */
    public function getActionId()
    {
        return property_exists($this, 'action_id') ? $this->action_id : '';
    }

    /**
     * Get token ID if exists.
     *
     * @return string
     */
    public function getTokenId()
    {
        return property_exists($this, 'token') ? $this->token : '';
    }

    /**
     * Verify if the request was successful.
     *
     * @return bool
     */
    public function isSuccessful()
    {
        return $this->getValue('http_code') < 400;
    }

    /**
     * Get error type.
     *
     * @return string
     */
    public function getErrorType()
    {
        return $this->getValue('error_type');
    }

    /**
     * Get list of errors.
     *
     * @return array
     */
    public function getErrors()
    {
        $errors = $this->getValue('error_codes');
        return $errors ? $errors : array();
    }

    /**
     * Get error code.
     *
     * @return int
     */
    public function getCode()
    {
        return (int) $this->getValue('http_code');
    }

    /**
     * Get a link from payment response.
     *
     * @param  string $key
     * @return string
     */
    public function getLink($key)
    {
        $link = $this->getValue(array('_links', $key, 'href'));
        if(!$link) {
            $link = '';
        }  

        return $link;
    }
}
