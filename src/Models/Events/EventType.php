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

namespace Checkout\Models\Events;

use Checkout\Library\Model;
use Checkout\Models\Response;

/**
 * Event type model.
 *
 * @category SDK
 * @package  Checkout.com
 * @author   Platforms Development Team <platforms@checkout.com>
 * @license  https://opensource.org/licenses/mit-license.html MIT License
 * @link     https://docs.checkout.com/
 */
class EventType extends Model
{

    /**
     * Qualified name of the class.
     *
     * @var string
     */
    const QUALIFIED_NAME = __CLASS__;

    /**
     * Name of the model.
     *
     * @var string
     */
    const MODEL_NAME = 'eventTypes';

    /**
     * API Request URL.
     *
     * @var string
     */
    const MODEL_REQUEST_URL = 'event-types';


    /**
     * Magic Methods
     */

    /**
     * Create response object.
     *
     * @param  array $response
     * @return Model
     */
    protected static function create(array $response)
    {
        if (is_int(key($response))) { // List of versions

            $obj = new Response();
            foreach ($response as $key => $type) {
                if (is_int($key)) {
                    $obj->list []= static::arrayToModel($type, static::QUALIFIED_NAME);
                }
            }
        } else {
            $obj = new self();
        }

        return $obj;
    }


    /**
     * Setter and getters
     */

    /**
     * Get list of types.
     *
     * @return array
     */
    public function getTypes()
    {
        $types = $this->getValue('event_types');
        return $types ? $types : array();
    }
}
