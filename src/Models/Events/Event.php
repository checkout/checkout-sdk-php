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
use Checkout\Library\Utilities;
use Checkout\Models\Response;

/**
 * Event model.
 *
 * @category SDK
 * @package  Checkout.com
 * @author   Platforms Development Team <platforms@checkout.com>
 * @license  https://opensource.org/licenses/mit-license.html MIT License
 * @link     https://docs.checkout.com/
 */
class Event extends Model
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
    const MODEL_NAME = 'event';

    /**
     * API Request URL.
     *
     * @var string
     */
    const MODEL_REQUEST_URL = 'events/{id}';


    /**
     * Magic Methods
     */

    /**
     * Initialize event.
     *
     * @param string $id
     */
    public function __construct($id)
    {
        if ($id === '') {
            $id = null;
        }

        $this->id = $id;
    }

    /**
     * Create response object.
     *
     * @param  array $response
     * @return Model
     */
    protected static function create(array $response)
    {      
        $code = Utilities::getValueFromArray($response, 'http_code', 0);
        if ($code === 204 || Utilities::getValueFromArray($response, 'total_count', 0)) {

            $obj = new Response();
            $obj->list = array();
            
            foreach (Utilities::getValueFromArray($response, 'data', array()) as &$event) {
                $obj->list []= static::arrayToModel($event, static::QUALIFIED_NAME);
            }

            unset($response['data']);
        } else {
            $obj = new self(Utilities::getValueFromArray($response, 'id', ''));
        }

        return $obj;
    }

    /**
     * Convert array into model. Second level properties will be array type.
     *
     * @param  array $arr
     * @return Model
     */
    protected static function arrayToModel(array $arr)
    {
        $obj = parent::arrayToModel($arr);
        unset($obj->data);
        return $obj;
    }
}
