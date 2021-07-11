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

namespace Checkout\Controllers;

use Checkout\Library\Controller;
use Checkout\Library\HttpHandler;
use Checkout\Models\Customer\Cust;
use Checkout\Models\Instruments\Details;

/**
 * Handle event controller.
 *
 * @category SDK
 * @package  Checkout.com
 * @author   Platforms Development Team <platforms@checkout.com>
 * @license  https://opensource.org/licenses/mit-license.html MIT License
 * @link     https://docs.checkout.com/
 */
class CustomerController extends Controller
{

    /**
     * Qualified name of the class.
     *
     * @var string
     */
    const QUALIFIED_NAME = __CLASS__;

    /**
     * Name of the controller.
     *
     * @var string
     */
    const CONTROLLER_NAME = 'customer';


    /**
     * Methods
     */


    /**
     * Get details.
     *
     * @param  string   id
     * @param  integer  $mode
     * @return mixed
     */
    public function details($id, $mode = HttpHandler::MODE_EXECUTE)
    {
        $details = new Details($id);
        $response = $this->requestAPI($details->getEndpoint());

        return $this->response($response, Instrument::QUALIFIED_NAME, $mode);
    }
}
