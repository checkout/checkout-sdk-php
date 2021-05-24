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
use Checkout\Models\Payments\Action;
use Checkout\Models\Payments\Capture;
use Checkout\Models\Payments\Details;
use Checkout\Models\Payments\Payment;
use Checkout\Models\Payments\Refund;
use Checkout\Models\Payments\Voids;
use Checkout\Models\Response;

/**
 * Make payments.
 *
 * @category SDK
 * @package  Checkout.com
 * @author   Platforms Development Team <platforms@checkout.com>
 * @license  https://opensource.org/licenses/mit-license.html MIT License
 * @link     https://docs.checkout.com/
 */
class PaymentController extends Controller
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
    const CONTROLLER_NAME = 'payment';

    /**
     * Field "source".
     *
     * @var string
     */
    const FIELD_SOURCE = 'source';

    /**
     * Field "type".
     *
     * @var string
     */
    const FIELD_SOURCE_TYPE = 'type';


    /**
     * Methods
     */

    /**
     * Void a payment.
     *
     * @param  Refund   $refund
     * @param  integer  $mode
     * @return mixed
     */
    public function refund(Refund $refund, $mode = HttpHandler::MODE_EXECUTE)
    {
        $response = $this->requestAPI($refund->getEndpoint())
            ->setBody($refund->getValues())
            ->setIdempotencyKey($refund->getIdempotencyKey());

        return $this->response($response, Refund::QUALIFIED_NAME, $mode, $refund->id);
    }

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

        return $this->response($response, Payment::QUALIFIED_NAME, $mode);
    }

    /**
     * Get actions.
     *
     * @param  string   id
     * @param  integer  $mode
     * @return mixed
     */
    public function actions($id, $mode = HttpHandler::MODE_EXECUTE)
    {
        $actions = new Action($id);
        $response = $this->requestAPI($actions->getEndpoint());

        return $this->response($response, Action::QUALIFIED_NAME, $mode);
    }

    /**
     * Void a payment.
     *
     * @param  Voids   $void
     * @param  integer  $mode
     * @return mixed
     */
    public function void(Voids $void, $mode = HttpHandler::MODE_EXECUTE)
    {
        $response = $this->requestAPI($void->getEndpoint())
            ->setBody($void->getValues())
            ->setIdempotencyKey($void->getIdempotencyKey());

        return $this->response($response, Voids::QUALIFIED_NAME, $mode, $void->id);
    }

    /**
     * Capture a payment.
     *
     * @param  Capture   $capture
     * @param  int      $mode
     * @return mixed
     */
    public function capture(Capture $capture, $mode = HttpHandler::MODE_EXECUTE)
    {
        $response = $this->requestAPI($capture->getEndpoint())
            ->setBody($capture->getValues())
            ->setIdempotencyKey($capture->getIdempotencyKey());

        return $this->response($response, Capture::QUALIFIED_NAME, $mode, $capture->id);
    }

    /**
     * Request a new payment.
     *
     * @param  Payment $payment
     * @param  integer $mode
     * @return mixed
     */
    public function request(Payment $payment, $mode = HttpHandler::MODE_EXECUTE)
    {
        $response = $this->requestAPI($payment->getEndpoint())
            ->setBody($payment->getValues())
            ->setIdempotencyKey($payment->getIdempotencyKey());

        return parent::response($response, Payment::QUALIFIED_NAME, $mode);
    }

    /**
     * Extra methods.
     */

    /**
     * Retrieve supported banks.
     *
     * @param  string $class Qualified name of the class.
     * @return Response
     */
    public function banks($class, $mode = HttpHandler::MODE_EXECUTE)
    {

        $banks = new Response();
        $url = $class::MODEL_REQUEST_BANKS_URL;

        if($url) {
            $response = $this->requestAPI($url);
            $banks = $this->response($response, Response::QUALIFIED_NAME, $mode);
        }

        return $banks;
    }

    /**
     * Retrieve supported issuers. Alias for $this->banks().
     *
     * @param  string $model
     * @return array
     */
    public function issuers($class, $mode = HttpHandler::MODE_EXECUTE) {
        return $this->banks($class, $mode);
    }

    /**
     * Handle the responses
     *
     * @param  HttpHandler  $response
     * @param  string $qualified
     * @param  int    $mode
     * @param  string $id
     * @return mixed
     */
    protected function response(HttpHandler $response, $qualified, $mode = HttpHandler::MODE_EXECUTE, $id = '')
    {
        $response = parent::response($response, $qualified, $mode);
        if ($id && $mode === HttpHandler::MODE_EXECUTE) {
            $response->id = $id;
        }

        return $response;
    }
}
