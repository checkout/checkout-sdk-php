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
use Checkout\Library\Utilities;
use Checkout\Models\Response;
use Checkout\Models\Webhooks\Webhook;
use Checkout\Models\Webhooks\WebhookHeaders;

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
        $body[static::FIELD_EVENTS] = Utilities::getValueFromArray($body, static::FIELD_EVENTS, array());

        if(!$events && !$body[static::FIELD_EVENTS]) {
            throw new CheckoutModelException('Field "event_types" is required to register a new webhook.');
        } else {
            $body[static::FIELD_EVENTS] = $body[static::FIELD_EVENTS] + $events;
        }

        unset($body[static::FIELD_ID]); // Remove ID from the body.
        $response = $this->requestAPI($webhook->getEndpoint())
            ->setBody($body);
        
        $response = $this->response($response, Webhook::QUALIFIED_NAME, $mode);

        if (isset($response->headers)) {
            $headers = new WebhookHeaders();
            foreach ($response->headers as $header => $value) {
                $headers->{lcfirst($header)} = $value;
            }
            $response->headers = $headers;
        }
        return $response;
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
        if(isset($webhook->headers) && !($webhook->headers instanceof WebhookHeaders)) {
            throw new CheckoutModelException('Field "headers" must be instance of WebhookHeaders.');
        }

        $body = $webhook->getValues();
        $body[static::FIELD_EVENTS] = Utilities::getValueFromArray($body, static::FIELD_EVENTS, array());
        $body[static::FIELD_ID] = Utilities::getValueFromArray($body, static::FIELD_ID, 0);

        if(!$body[static::FIELD_EVENTS]) {
            throw new CheckoutModelException('Field "event_types" is required to register a new webhook.');
        }
        if (!$body[static::FIELD_ID]) {
            throw new CheckoutModelException('Field "id" is required for webhook update.');
        }
        if (!$partially && !isset($body['active'])) {
            throw new CheckoutModelException('Field "active" is required for webhook update.');
        }
        if (!$partially && !isset($body['headers'])) {
            throw new CheckoutModelException('Field "headers" is required for webhook update.');
        }
        if (!$partially && !isset($body['content_type'])) {
            throw new CheckoutModelException('Field "content_type" is required for webhook update.');
        }

        unset($body[static::FIELD_ID]); // Remove id from the body.
        $response = $this->requestAPI($webhook->getEndpoint())
            ->setBody($body)
            ->setMethod($partially ? HttpHandler::METHOD_PATCH : HttpHandler::METHOD_PUT);

        $response = $this->response($response, Webhook::QUALIFIED_NAME, $mode);

        if (isset($response->headers)) {
            $headers = new WebhookHeaders();
            foreach ($response->headers as $header => $value) {
                $headers->{lcfirst($header)} = $value;
            }
            $response->headers = $headers;
        }
        return $response;
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
