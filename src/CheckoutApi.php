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

namespace Checkout;

use Checkout\Controllers\EventController;
use Checkout\Controllers\FileController;
use Checkout\Controllers\PaymentController;
use Checkout\Controllers\SourceController;
use Checkout\Controllers\TokenController;
use Checkout\Controllers\WebhookController;
use Checkout\Library\CheckoutConfiguration;
use Checkout\Library\Controller;
use Checkout\Library\HttpHandler;
use Checkout\Library\LogHandler;
use Checkout\Library\Model;
use Checkout\Library\Utilities;

/**
 * Wrapper controller class of Checkout.com SDK.
 *
 * @category SDK
 * @package  Checkout.com
 * @author   Platforms Development Team <platforms@checkout.com>
 * @license  https://opensource.org/licenses/mit-license.html MIT License
 * @link     https://docs.checkout.com/
 */
final class CheckoutApi
{

    /**
     * Version of the SDK.
     *
     * @var string
     */
    const VERSION = '1.0.0';

    /**
     * Secret key.
     *
     * @var string
     */
    const CONFIG_SECRET = 'secret_key';

    /**
     * Public key.
     *
     * @var string
     */
    const CONFIG_PUBLIC = 'public_key';

    /**
     * Logging.
     *
     * @var string
     */
    const CONFIG_LOGGING = 'logging';

    /**
     * Logging.
     *
     * @var string
     */
    const CONFIG_LOG_REQUEST = 'request';

    /**
     * Response.
     *
     * @var string
     */
    const CONFIG_LOG_RESPONSE = 'response';

    /**
     * Error.
     *
     * @var string
     */
    const CONFIG_LOG_ERROR = 'error';


    /**
     * Properties
     */

    /**
     * Channel to be handled.
     *
     * @var CheckoutConfiguration
     */
    private $configurator;


    /**
     * Magic methods
     */

    /**
     * Initialise Checkout API SDK.
     *
     * @param string $secret
     * @param int    $sandbox
     * @param string $public
     */
    public function __construct(
        $secret = '',
        $sandbox = -1,
        $public = '',
        $configs = []
    )
    {
        $configs = array_merge(
            Utilities::loadConfig(__DIR__ . DIRECTORY_SEPARATOR . 'config.ini'),
            $configs
        );
        
        if (isset($configs['channel'])) {
            $this->loadChannel($configs['channel'], $secret, $sandbox, $public);
        }

        if (isset($configs['logs'])) {
            $this->loadLogs($configs['logs']);
        }

        if (isset($configs['aliases'])) {
            $this->loadAliases($configs['aliases']);
        }

        if (isset($configs['curl'])) {
            $this->loadCurl($configs['curl']);
        }

        if (isset($configs['http'])) {
            $this->loadHttp($configs['http']);
        }
    }


    /**
     * Controller Methods
     */

    /**
     * Handle payment attribute.
     *
     * @param  CheckoutConfiguration $configuration
     * @return PaymentController
     */
    public function payments(CheckoutConfiguration $configuration = null)
    {
        return $this->controller(PaymentController::QUALIFIED_NAME, $configuration);
    }

    /**
     * Handle token attribute.
     *
     * @param  CheckoutConfiguration $configuration
     * @return TokenController
     */
    public function tokens(CheckoutConfiguration $configuration = null)
    {
        return $this->controller(TokenController::QUALIFIED_NAME, $configuration);
    }

    /**
     * Handle source controller.
     *
     * @param  CheckoutConfiguration $configuration
     * @return SourceController
     */
    public function sources(CheckoutConfiguration $configuration = null)
    {
        return $this->controller(SourceController::QUALIFIED_NAME, $configuration);
    }

    /**
     * Handle file controller.
     *
     * @param  CheckoutConfiguration $configuration
     * @return FileController
     */
    public function files(CheckoutConfiguration $configuration = null)
    {
        return $this->controller(FileController::QUALIFIED_NAME, $configuration);
    }

    /**
     * Handle webhook controller.
     *
     * @param  CheckoutConfiguration $configuration
     * @return WebhookController
     */
    public function webhooks(CheckoutConfiguration $configuration = null)
    {
        return $this->controller(WebhookController::QUALIFIED_NAME, $configuration);
    }

    /**
     * Handle event controller.
     *
     * @param  CheckoutConfiguration $configuration
     * @return EventController
     */
    public function events(CheckoutConfiguration $configuration = null)
    {
        return $this->controller(EventController::QUALIFIED_NAME, $configuration);
    }


    /**
     * Setters and Getters
     */

    /**
     * Set a new channel.
     *
     * @param  CheckoutConfiguration $configuration
     * @return CheckoutConfiguration
     */
    public function configuration(CheckoutConfiguration $configuration = null)
    {
        return $this->configurator = ($configuration ? $configuration : $this->configurator);
    }


    /**
     * Methods
     */

    /**
     * Initialises controllers.
     *
     * @param  string                $qualified
     * @param  CheckoutConfiguration $configuration
     * @return Controller
     */
    private function controller($qualified, CheckoutConfiguration $configuration = null)
    {
        if ($configuration === null) {
            $configuration = $this->configurator;
        }
        return $this->{$qualified::CONTROLLER_NAME} = $qualified::create($configuration);
    }

    /**
     * Load configuration for channel.
     *
     * @param  array  $configs
     * @param  string $secret
     * @param  int    $sandbox
     * @param  string $public
     * @return void
     */
    private function loadChannel(array $configs, $secret, $sandbox, $public)
    {
        $defaults = array(static::CONFIG_SECRET         => '',
                          static::CONFIG_PUBLIC         => '',
                          CheckoutConfiguration::ENVIRONMENT_SANDBOX => true);

        foreach ($configs as $key => $value) {
            $defaults[$key] = $value;
        }

        if ($secret) { // Override secret
            $defaults[static::CONFIG_SECRET] = $secret;
        }
        if ($sandbox !== -1) { // Override environment
            $defaults[CheckoutConfiguration::ENVIRONMENT_SANDBOX] = (bool) $sandbox;
        }
        if ($public) { // Override public
            $defaults[static::CONFIG_PUBLIC] = $public;
        }

        $this->configurator = new CheckoutConfiguration(
            $defaults[static::CONFIG_SECRET],
            $defaults[CheckoutConfiguration::ENVIRONMENT_SANDBOX],
            $defaults[static::CONFIG_PUBLIC]
        );
    }

    /**
     * Load configuration for logging.
     *
     * @param array $configs
     */
    private function loadLogs(array $configs)
    {
        $defaults = array(static::CONFIG_LOGGING       => true,
                          static::CONFIG_LOG_REQUEST   => 'request.log',
                          static::CONFIG_LOG_RESPONSE  => 'response.log',
                          static::CONFIG_LOG_ERROR     => 'error.log');

        foreach ($configs as $key => $value) {
            $defaults[$key] = $value;
        }

        if ($defaults[static::CONFIG_LOGGING]) {
            LogHandler::$error = $defaults[static::CONFIG_LOG_ERROR];
            LogHandler::$request = $defaults[static::CONFIG_LOG_REQUEST];
            LogHandler::$response = $defaults[static::CONFIG_LOG_RESPONSE];
        } else {
            LogHandler::$error = null;
            LogHandler::$request = null;
            LogHandler::$response = null;
        }
    }

    /**
     * Load configuration for alias.
     *
     * @param array $configs
     */
    private function loadAliases(array $configs)
    {
        $defaults = array('threeDs' => '3ds');

        foreach ($configs as $key => $value) {
            $defaults[$key] = $value;
        }

        Model::$aliases = $defaults;
    }

    /**
     * Load configuration for curl.
     *
     * @param array $configs
     */
    private function loadCurl(array $configs)
    {
        $defaults = array('CURLOPT_FAILONERROR' => false,
                          'CURLOPT_RETURNTRANSFER' => true,
                          'CURLOPT_CONNECTTIMEOUT' => 60);

        foreach ($configs as $key => $value) {
            $defaults[$key] = $value;
        }

        HttpHandler::$config = $defaults;
    }

    /**
     * Load configuration for curl.
     *
     * @param array $configs
     */
    private function loadHttp(array $configs)
    {
        $defaults = array('exceptions' => true);

        foreach ($configs as $key => $value) {
            $defaults[$key] = $value;
        }

        HttpHandler::$throw = $defaults['exceptions'];
    }
}
