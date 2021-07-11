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

namespace Checkout\Models\Instruments;

use Checkout\Library\HttpHandler;
use Checkout\Library\Model;

/**
 * Instrument Details model.
 *
 * @category SDK
 * @package  Checkout.com
 * @author   Platforms Development Team <platforms@checkout.com>
 * @license  https://opensource.org/licenses/mit-license.html MIT License
 * @link     https://docs.checkout.com/
 */
class Details extends Model
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
    const MODEL_NAME = 'details';

    /**
     * API Request URL.
     *
     * @var string
     */
    const MODEL_REQUEST_URL = 'instruments/{id}';

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
}
