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

namespace Checkout\Models\Payments;

use Checkout\Library\Model;

/**
 * Parent class for Source and Destination.
 *
 * @category SDK
 * @package  Checkout.com
 * @author   Platforms Development Team <platforms@checkout.com>
 * @license  https://opensource.org/licenses/mit-license.html MIT License
 * @link     https://docs.checkout.com/
 */
abstract class Idempotency extends Model
{

    /**
     * Qualified name of the class.
     *
     * @var string
     */
    const QUALIFIED_NAME = __CLASS__;

    /**
     * Method type.
     *
     * @var string
     */
    const METHOD_TYPE = '';

    /**
     * Idempotency key.
     *
     * @var string
     */
    protected $idempotency = '';


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
        $values = parent::getValues();
        unset($values['idempotency']);
        return $values;
    }

    /**
     * Set Idempotency key.
     *
     * @note    Be cautious when using idempotency keys.
     *          If we detect concurrent requests with the same idempotency key,
     *          only one request will be processed and the other requests
     *          will return a 429 - Too Many Requests response.
     * @param  string $key
     * @return self $this
     */
    public function setIdempotencyKey($key)
    {
        $this->idempotency = $key;
        return $this;
    }

    /**
     * Get Idempotency key.
     *
     * @return string $key
     */
    public function getIdempotencyKey()
    {
        return $this->idempotency;
    }

    /**
     * Remove idempotency key from the object.
     *
     * @return void
     */
    public function removeIdempotencyKey()
    {
        unset($this->idempotency);
    }

}
