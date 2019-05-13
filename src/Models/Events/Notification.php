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

namespace Checkout\Models\Events;

use Checkout\Library\Model;

/**
 * Event notification model.
 *
 * @category SDK
 * @package  Checkout.com
 * @author   Platforms Development Team <platforms@checkout.com>
 * @license  https://opensource.org/licenses/mit-license.html MIT License
 * @link     https://docs.checkout.com/
 */
class Notification extends Model
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
    const MODEL_NAME = 'notifications';

    /**
     * API Request URL.
     *
     * @var string
     */
    const MODEL_REQUEST_URL = 'events/{event}/notifications/{id}';


    /**
     * Magic Methods
     */

    /**
     * Initialize notification.
     *
     * @param string $event
     * @param string $id
     */
    public function __construct($event, $id)
    {
        $this->event = $event;
        $this->id = $id;
    }
}
