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
use Checkout\Models\Events\Event;
use Checkout\Models\Events\EventType;
use Checkout\Models\Events\Notification;
use Checkout\Models\Events\Webhook;
use Checkout\Models\Response;

/**
 * Handle event controller.
 *
 * @category SDK
 * @package  Checkout.com
 * @author   Platforms Development Team <platforms@checkout.com>
 * @license  https://opensource.org/licenses/mit-license.html MIT License
 * @link     https://docs.checkout.com/
 */
class EventController extends Controller
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
    const CONTROLLER_NAME = 'event';

    /**
     * Field 'id'.
     *
     * @var string
     */
    const FIELD_ID = 'id';


    /**
     * Methods
     */

    /**
     * Load a event.
     *
     * @param  string $id
     * @param  bool   $mode
     * @return mixed
     */
    public function load($id, $mode = HttpHandler::MODE_EXECUTE)
    {
        $event = new Event($id);
        $response = $this->requestAPI($event->getEndpoint());

        return $this->response($response, Event::QUALIFIED_NAME, $mode);
    }

    /**
     * Get list all events.
     *
     * @param  array $params
     * @param  bool  $mode
     * @return mixed
     */
    public function retrieve(array $params = array(), $mode = HttpHandler::MODE_EXECUTE)
    {
        $event = new Event('');
        $response = $this->requestAPI($event->getEndpoint())
            ->setQueryParameters($params);

        return $this->response($response, Event::QUALIFIED_NAME, $mode);
    }

    /**
     * Load a notification.
     *
     * @param  string $event Event ID.
     * @param  string $id    Notification ID.
     * @param  bool   $mode
     * @return mixed
     */
    public function notification($event, $id, $mode = HttpHandler::MODE_EXECUTE)
    {
        $notification = new Notification($event, $id);
        $response = $this->requestAPI($notification->getEndpoint());

        return $this->response($response, Event::QUALIFIED_NAME, $mode);
    }

    /**
     * Load a event or list all events.
     *
     * @param  array $params
     * @param  bool  $mode
     * @return mixed
     */
    public function types(array $params = array(), $mode = HttpHandler::MODE_EXECUTE)
    {
        $event = new EventType();
        $response = $this->requestAPI($event->getEndpoint())
            ->setQueryParameters($params);

        return $this->response($response, EventType::QUALIFIED_NAME, $mode);
    }

    /**
     * Retry webhook or all webhooks.
     *
     * @param  string $event
     * @param  string $id
     * @param  bool   $mode
     * @return mixed
     */
    public function webhook($event, $id = '', $mode = HttpHandler::MODE_EXECUTE)
    {
        $webhook = new Webhook($event, $id);
        $response = $this->requestAPI($webhook->getEndpoint())
            ->setMethod(HttpHandler::METHOD_POST);

        return $this->response($response, Response::QUALIFIED_NAME, $mode);
    }
}
