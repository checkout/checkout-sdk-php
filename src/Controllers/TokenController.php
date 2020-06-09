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
use Checkout\Models\Tokens\Token;

/**
 * Handle tokens.
 * Exchange card details or a digital wallet payment token for a reference token
 * that can be later used to request a card payment.
 *
 * @category SDK
 * @package  Checkout.com
 * @author   Platforms Development Team <platforms@checkout.com>
 * @license  https://opensource.org/licenses/mit-license.html MIT License
 * @link     https://docs.checkout.com/
 */
class TokenController extends Controller
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
    const CONTROLLER_NAME = 'token';

    /**
     * Field name "token data".
     *
     * @var string
     */
    const FIELD_TOKEN_DATA = 'token_data';

    /**
     * Field name "type".
     *
     * @var string
     */
    const FIELD_TYPE = 'type';


    /**
     * Methods
     */

    /**
     * Request a new token. Valid for 15 min.
     *
     * @param  Token $token
     * @param  bool  $mode
     * @return mixed
     */
    public function request(Token $token, $mode = HttpHandler::MODE_EXECUTE)
    {
        $body = array(static::FIELD_TYPE => $token::MODEL_NAME);
        $body += $token->getValues();

        $response = $this->requestAPI($token->getEndpoint(), HttpHandler::AUTH_TYPE_PUBLIC)
            ->setBody($body);

        return $this->response($response, $token::QUALIFIED_NAME, $mode);
    }
}
