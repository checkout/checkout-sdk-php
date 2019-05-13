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

use Checkout\Library\HttpHandler;
use Checkout\Library\Model;
use Checkout\Models\Response;

/**
 * Payment actions model.
 *
 * @category SDK
 * @package  Checkout.com
 * @author   Platforms Development Team <platforms@checkout.com>
 * @license  https://opensource.org/licenses/mit-license.html MIT License
 * @link     https://docs.checkout.com/
 */
class Action extends Model
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
    const MODEL_NAME = 'actions';

    /**
     * API Request URL.
     *
     * @var string
     */
    const MODEL_REQUEST_URL = 'payments/{id}/actions';

    /**
     * API Request Method.
     *
     * @var string
     */
    const MODEL_REQUEST_METHOD = HttpHandler::METHOD_GET;


    /**
     * Magic Methods
     */

    /**
     * Initialise Details
     *
     * @param string $id
     */
    public function __construct($id)
    {
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
        $obj = new Response();
        if (is_int(key($response))) { // List of actions

            foreach ($response as $key => $type) {
                if (is_int($key)) {
                    $obj->list []= static::arrayToModel($type);
                }
            }
        } else {
            $obj = new Action('');
        }

        return $obj;
    }
}
