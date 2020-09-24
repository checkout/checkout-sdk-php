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
use Checkout\Models\Response;

/**
 * Base payment model.
 *
 * @category SDK
 * @package  Checkout.com
 * @author   Platforms Development Team <platforms@checkout.com>
 * @license  https://opensource.org/licenses/mit-license.html MIT License
 * @link     https://docs.checkout.com/
 */
class Payment extends Idempotency
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
    const MODEL_NAME = 'payment';

    /**
     * API Request URL.
     *
     * @var string
     */
    const MODEL_REQUEST_URL = 'payments';

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
     * Initialise payment handler.
     *
     * @param Method    $method
     * @param string    $currency
     */
    public function __construct(Method $method, $currency)
    {
        $this->{$method::METHOD_TYPE} = $method;
        $this->currency = $currency;
    }

    /**
     * Create response object.
     *
     * @param  array $response
     * @return Model
     */
    protected static function create(array $response)
    {
        return new self(
            new IdSource(''),
            Utilities::getValueFromArray($response, 'currency', '')
        );
    }


    /**
     * Methods
     */

    /**
     * Verify if the request was successful.
     *
     * @return bool
     */
    public function isSuccessful()
    {
        return parent::isSuccessful() && static::isApproved();
    }

    /**
     * Verify is request was approved.
     * @note    Will return true if not present.
     * @return  bool
     */
    public function isApproved()
    {
        $approved = true;
        if ($this->getValue('approved') !== null) {
            $approved = $this->getValue('approved');
        }

        return $approved;
    }

    /**
     * Verify if the request is pending.
     *
     * @return bool
     */
    public function isPending()
    {
        return $this->getCode() === 202;
    }

    /**
     * Verify if the request was valid.
     *
     * @return bool
     */
    public function isValid()
    {
        return $this->getCode() !== 422;
    }

    /**
     * Verify if the payment is risky.
     *
     * @return bool
     */
    public function isFlagged()
    {
        $flagged = false;
        $risk = $this->getValue('risk');

        if (isset($risk['flagged'])) {
            $flagged = $risk['flagged'];
        }

        return $flagged;
    }

    /**
     * Convert array into model.
     *
     * @param  array $arr
     * @param  mixed $qualified
     * @return self
     */
    protected static function arrayToModel(
        array $arr,
        $qualified = Response::QUALIFIED_NAME
    ) {
        $obj = parent::arrayToModel($arr, $qualified);
        if (static::QUALIFIED_NAME === $obj::QUALIFIED_NAME) {
            $obj->removeIdempotencyKey();
        }

        return $obj;
    }


    /**
     * Setters and Getters
     */

    /**
     * Get redirection of payment response.
     *
     * @return string
     */
    public function getRedirection()
    {
        return $this->getLink('redirect');
    }

    /**
     * Get source id when is available.
     *
     * @return string
     */
    public function getSourceId()
    {
        $source = $this->getValue('source');
        $id = '';
        if (isset($source['id'])) {
            $id = $source['id'];
        }

        return $id;
    }
}
