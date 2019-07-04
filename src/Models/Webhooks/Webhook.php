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

namespace Checkout\Models\Webhooks;

use Checkout\Library\Model;
use Checkout\Library\Utilities;
use Checkout\Models\Response;

/**
 * Webhook model.
 *
 * @category SDK
 * @package  Checkout.com
 * @author   Platforms Development Team <platforms@checkout.com>
 * @license  https://opensource.org/licenses/mit-license.html MIT License
 * @link     https://docs.checkout.com/
 */
class Webhook extends Model
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
    const MODEL_NAME = 'webhook';

    /**
     * API Request URL.
     *
     * @var string
     */
    const MODEL_REQUEST_URL = 'webhooks/{id}';


    /**
     * Magic Methods
     */

    /**
     * Initialize webhook.
     *
     * @param string $url
     * @param string $id
     */
    public function __construct($url, $id = '')
    {
        $this->url = $url;
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
        if ($code === 204 || is_int(key($response))) { // List of webhooks

            $obj = new Response();
            $obj->list = array();
            
            foreach ($response as $key => $webhook) {
                if (is_int($key)) {
                    $obj->list []= static::arrayToModel($webhook, static::QUALIFIED_NAME);
                }
            }
        } else {
            $obj = new self(
                Utilities::getValueFromArray($response, 'url', ''),
                Utilities::getValueFromArray($response, 'id', '')
            );
        }

        return $obj;
    }
}
