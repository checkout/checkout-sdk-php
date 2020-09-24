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
use Checkout\Library\Utilities;

/**
 * Capture payment.
 *
 * @category SDK
 * @package  Checkout.com
 * @author   Platforms Development Team <platforms@checkout.com>
 * @license  https://opensource.org/licenses/mit-license.html MIT License
 * @link     https://docs.checkout.com/
 */
class Capture extends Idempotency
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
    const MODEL_NAME = 'capture';

    /**
     * API Request URL.
     *
     * @var string
     */
    const MODEL_REQUEST_URL = 'payments/{id}/captures';

    /**
     * API Request Method.
     *
     * @var string
     */
    const MODEL_REQUEST_METHOD = HttpHandler::METHOD_POST;


    /**
     * Magic Methods
     */

    /**
     * Initialise Voids
     *
     * @param string   $id
     * @param int      $amount
     * @param string   $reference
     * @param Metadata $meta
     */
    public function __construct($id, $amount = null, $reference = null, Metadata $meta = null)
    {
        $this->id = $id;
        $this->amount = $amount;
        $this->reference = $reference;
        $this->metadata = $meta;
    }

    /**
     * Create Capture object.
     *
     * @param  array $response
     * @return Model
     */
    protected static function create(array $response)
    {
        return new self(
            Utilities::getValueFromArray($response, 'id', ''),
            Utilities::getValueFromArray($response, 'amount', null),
            Utilities::getValueFromArray($response, 'reference', ''),
            Utilities::getValueFromArray($response, 'metadata', new Metadata)
        );
    }
}
