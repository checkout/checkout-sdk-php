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
     * Initialise Apple Pay.
     * @param string $version           Version information about the payment token. 
     *                                  The token uses EC_v1 for ECC-encrypted data, and RSA_v1 for RSA-encrypted data.
     * @param string $signature         Signature of the payment and header data. 
     *                                  The signature includes the signing certificate, its intermediate CA certificate, and information about the signing algorithm.
     * @param string $data              Encrypted payment data. Base64 encoded as a string.
     * @param ApplePayHeader $header    Additional version-dependent information used to decrypt and verify the payment.
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
