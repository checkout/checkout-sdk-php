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

namespace Checkout\Models\Tokens;

use Checkout\Library\Model;
use Checkout\Library\Utilities;

/**
 * ApplePay payment method.
 *
 * @category SDK
 * @package  Checkout.com
 * @author   Platforms Development Team <platforms@checkout.com>
 * @license  https://opensource.org/licenses/mit-license.html MIT License
 * @link     https://docs.checkout.com/
 */
class ApplePay extends Token
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
    const MODEL_NAME = 'applepay';


    /**
     * Magic Methods
     */

    /**
     * Initilize Apple Pay.
     * @param string $version
     * @param string $signature
     * @param string $data
     * @param ApplePayHeader $header
     */
    public function __construct($version, $signature, $data, ApplePayHeader $header)
    {
        $this->token_data = (object) array('version'    => $version,
                                           'signature'  => $signature,
                                           'data'       => $data,
                                           'header'     => $header);
    }

    /**
     * Create response object.
     *
     * @param  array $response
     *
     * @return Model
     */
    protected static function create(array $response)
    {
        $header = new ApplePayHeader('', '', '');
        return new self(
            Utilities::getValueFromArray($response, 'version', ''),
            Utilities::getValueFromArray($response, 'signature', ''),
            Utilities::getValueFromArray($response, 'data', ''),
            Utilities::getValueFromArray($response, 'header', $header)
        );
    }
}
