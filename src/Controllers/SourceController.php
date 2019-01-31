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

namespace Checkout\Controllers;

use Checkout\Library\Controller;
use Checkout\Library\HttpHandler;
use Checkout\Models\Sources\Source;

/**
 * Add a payment source.
 *
 * Add a reusable payment sources can be later used to make one or more payments.
 *
 * @category SDK
 * @package  Checkout.com
 * @author   Platforms Development Team <platforms@checkout.com>
 * @license  https://opensource.org/licenses/mit-license.html MIT License
 * @link     https://docs.checkout.com/
 */
class SourceController extends Controller
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
    const CONTROLLER_NAME = 'source';


    /**
     * Methods
     */

    /**
     * Add a payment source.
     *
     * @param  Source $source
     * @param  bool   $mode
     * @return mixed
     */
    public function add(Source $source, $mode = HttpHandler::MODE_EXECUTE)
    {
        $response = $this->requestAPI($source->getEndpoint())
            ->setBody($source->getValues());

        return $this->response($response, $source::QUALIFIED_NAME, $mode);
    }
}
