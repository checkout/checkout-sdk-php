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

namespace Checkout\Library;

use Checkout\Library\HttpHandler;

/**
 * Controller parent class.
 *
 * @category SDK
 * @package  Checkout.com
 * @author   Platforms Development Team <platforms@checkout.com>
 * @license  https://opensource.org/licenses/mit-license.html MIT License
 * @link     https://docs.checkout.com/
 */
abstract class Controller
{

    /**
     * Target channel.
     *
     * @var CheckoutConfiguration
     */
    protected $configuration;

    /**
     * Magic Methods
     */

    /**
     * Block access to constructor. Use static::create() instead.
     *
     * @param CheckoutConfiguration $configuration
     */
    protected function __construct(CheckoutConfiguration $configuration)
    {
        $this->configuration = $configuration;
    }


    /**
     * Methods
     */

    /**
     * Create new instance of the model.
     *
     * @return self new static
     */
    public static function create(CheckoutConfiguration $configuration)
    {
        return new static($configuration);
    }

    /**
     * Make a request to API.
     *
     * @param  string $url
     * @param  int    $auth
     * @return HttpHandler
     */
    protected function requestAPI($url, $auth = HttpHandler::AUTH_TYPE_SECRET)
    {
        return HttpHandler::create($url)->setConfiguration($this->configuration, $auth);
    }

    /**
     * Handle the responses
     *
     * @param  HttpHandler  $response
     * @param  string $qualified
     * @param  int    $mode
     * @return mixed
     */
    protected function response(HttpHandler $response, $qualified, $mode = HttpHandler::MODE_EXECUTE)
    {
        if ($mode === HttpHandler::MODE_EXECUTE) {
            $response = $qualified::load($response->execute());
        }

        return $response;
    }
}
