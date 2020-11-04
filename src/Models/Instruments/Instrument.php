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
 * @copyright 2010-2021 Checkout.com
 * @license   https://opensource.org/licenses/mit-license.html MIT License
 * @link      https://docs.checkout.com/
 */

namespace Checkout\Models\Instruments;

use Checkout\Library\Model;
use Checkout\Library\Utilities;
use Checkout\Models\Response;

/**
 * Instrument model.
 *
 * @category SDK
 * @package  Checkout.com
 * @author   Platforms Development Team <platforms@checkout.com>
 * @license  https://opensource.org/licenses/mit-license.html MIT License
 * @link     https://docs.checkout.com/
 */
class Instrument extends Model
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
    const MODEL_NAME = 'instrument';

    /**
     * API Request URL.
     *
     * @var string
     */
    const MODEL_REQUEST_URL = 'instruments';


    /**
     * Methods
     */

    /**
     * Initialize instrument.
     *
     * @param  Instrument $instrument
     */
    public function __construct($type, $token)
    {
        if ($type === '') {
            $type = null;
        }

        if ($token === '') {
            $token = null;
        }

        $this->type = $type;
        $this->token = $token;
    }

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
                    $obj->list[] = static::arrayToModel($type, static::QUALIFIED_NAME);
                }
            }
        } else {
            $obj = new self(Utilities::getValueFromArray($response, 'type'), Utilities::getValueFromArray($response, 'token'));
        }

        return $obj;
    }
}
