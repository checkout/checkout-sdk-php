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
use Checkout\Library\Exceptions\CheckoutModelException;
use Checkout\Library\HttpHandler;
use Checkout\Models\Response;
use Checkout\Models\Webhooks\Webhook;

/**
 * Webhook controller.
 *
 * @category SDK
 * @package  Checkout.com
 * @author   Platforms Development Team <platforms@checkout.com>
 * @license  https://opensource.org/licenses/mit-license.html MIT License
 * @link     https://docs.checkout.com/
 */
class WebhookController extends Controller
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
    const CONTROLLER_NAME = 'webhook';

    /**
     * Field 'id'.
     *
     * @var string
     */
    const FIELD_ID = 'id';

    /**
     * Field 'id'.
     *
     * @var string
     */
    const FIELD_EVENTS = 'event_types';


    /**
     * Methods
     */

    /**
     * Load a webhook.
     *
     * @param  string $id
     * @param  bool   $mode
     * @return mixed
     */
    public function load($id, $mode = HttpHandler::MODE_EXECUTE)
    {
        $webhook = new Webhook('', $id);
        $response = $this->requestAPI($webhook->getEndpoint());

        return $this->response($response, Webhook::QUALIFIED_NAME, $mode);
    }

    /**
     * Retrieve all webhooks.
     *
     * @param  bool $mode
     * @return mixed
     */
    public function retrieve($mode = HttpHandler::MODE_EXECUTE)
    {
        $webhook = new Webhook('');
        $response = $this->requestAPI($webhook->getEndpoint());

        return $this->response($response, Webhook::QUALIFIED_NAME, $mode);
    }

    /**
     * Create a new webhook.
     *
     * @param  Webhook $webhook
     * @param  array   $events
     * @param  bool    $mode
     * @return mixed
     */
    public function register(Webhook $webhook, array $events = array(), $mode = HttpHandler::MODE_EXECUTE)
    {
        $body = $webhook->getValues();

        if ((!isset($body[static::FIELD_EVENTS]) || (isset($body[static::FIELD_EVENTS]) && !$body[static::FIELD_EVENTS])) && !$events) {
            throw new CheckoutModelException('Field "event_types" is required to register a new webhook.');
        } elseif (!isset($body[static::FIELD_EVENTS])) {
            $body[static::FIELD_EVENTS] = array();
        }

        $merged = array_merge($body[static::FIELD_EVENTS], $events);
        $body[static::FIELD_EVENTS] = array_values($merged);

        unset($body[static::FIELD_ID]); // Remove ID from the body.
        $response = $this->requestAPI($webhook->getEndpoint())
            ->setBody($body);

        return $this->response($response, Webhook::QUALIFIED_NAME, $mode);
    }

    /**
     * Create a new webhook.
     *
     * @param  Webhook $webhook
     * @param  bool    $partially
     * @param  bool    $mode
     * @return mixed
     */
    public function update(Webhook $webhook, $partially = false, $mode = HttpHandler::MODE_EXECUTE)
    {
        $body = $webhook->getValues();
        if (!isset($body[static::FIELD_ID]) || !$body[static::FIELD_ID]) {
            throw new CheckoutModelException('Field "id" is required for webhook update.');
        }
        unset($body[static::FIELD_ID]); // Remove id from the body.
        $response = $this->requestAPI($webhook->getEndpoint())
            ->setBody($body)
            ->setMethod(!$partially ? HttpHandler::METHOD_PUT : HttpHandler::METHOD_PATCH);

        return $this->response($response, Webhook::QUALIFIED_NAME, $mode);
    }

    /**
     * Remove webhook.
     *
     * @param  string $id
     * @param  bool   $mode
     * @return mixed
     */
    public function remove($id, $mode = HttpHandler::MODE_EXECUTE)
    {
        $webhook = new Webhook('', $id);
        $response = $this->requestAPI($webhook->getEndpoint())
            ->setMethod(HttpHandler::METHOD_DEL);

        return $this->response($response, Response::QUALIFIED_NAME, $mode);
    }
}
