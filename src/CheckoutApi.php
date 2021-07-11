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
use Checkout\Controllers\InstrumentController;
use Checkout\Controllers\FileController;
use Checkout\Controllers\PaymentController;
use Checkout\Controllers\SourceController;
use Checkout\Controllers\TokenController;
use Checkout\Controllers\WebhookController;
use Checkout\Controllers\CustomerController;

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
    const VERSION = '1.0.16';

    /**
     * Channel section.
     *
     * @var string
     */
    const CONFIG_SECTION_CHANNEL = 'channel';

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
     * Log section.
     *
     * @var string
     */
    const CONFIG_SECTION_LOGS = 'logs';

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
     * Aliases section.
     *
     * @var string
     */
    const CONFIG_SECTION_ALIASES = 'aliases';

    /**
     * CURL section.
     *
     * @var string
     */
    const CONFIG_SECTION_CURL = 'curl';

    /**
     * HTTP section.
     *
     * @var string
     */
    const CONFIG_SECTION_HTTP = 'http';


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
     * @param boolean $sandbox
     * @param string $public
     * @param mixed $config    Path or array of custom configuration.
     */
    public function __construct($secret = '', $sandbox = -1, $public = '', $config = __DIR__ . DIRECTORY_SEPARATOR . 'config.ini')
    {

        $configs = Utilities::loadConfig($config);

        $this->loadChannel($secret, $sandbox, $public, $configs);
        $this->loadLogs($configs);
        $this->loadAliases($configs);
        $this->loadCurl($configs);
        $this->loadHttp($configs);

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
     * Handle instruments controller.
     *
     * @param  CheckoutConfiguration $configuration
     * @return InstrumentController
     */
    public function instruments(CheckoutConfiguration $configuration = null)
    {
        return $this->controller(InstrumentController::QUALIFIED_NAME, $configuration);
    }

    /**
     * Handle customers controller.
     *
     * @param  CheckoutConfiguration $configuration
     * @return CustomerController
     */
    public function customers(CheckoutConfiguration $configuration = null)
    {
        return $this->controller(CustomerController::QUALIFIED_NAME, $configuration);
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
    public function configuration (CheckoutConfiguration $configuration = null)
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
    private function controller ($qualified, CheckoutConfiguration $configuration = null)
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
    private function loadChannel ($secret, $sandbox, $public, array &$configs = array())
    {

        $defaults = array(static::CONFIG_SECRET         => '',
                          static::CONFIG_PUBLIC         => '',
                          CheckoutConfiguration::ENVIRONMENT_SANDBOX => true);

        $this->safelyArrayMerge(static::CONFIG_SECTION_CHANNEL, $defaults, $configs);

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
     * @param void
     */
    private function loadLogs (array &$configs)
    {

        $defaults = array(static::CONFIG_LOGGING       => false,
                          static::CONFIG_LOG_REQUEST   => 'request.log',
                          static::CONFIG_LOG_RESPONSE  => 'response.log',
                          static::CONFIG_LOG_ERROR     => 'error.log');

        $this->safelyArrayMerge(static::CONFIG_SECTION_LOGS, $defaults, $configs);

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
    private function loadAliases (array &$configs)
    {

        $defaults = array('threeDs' => '3ds');

        $this->safelyArrayMerge(static::CONFIG_SECTION_ALIASES, $defaults, $configs);

        Model::$aliases = $defaults;
    }

    /**
     * Load configuration for curl.
     *
     * @param array $configs
     */
    private function loadCurl (array &$configs)
    {
        $defaults = array('CURLOPT_FAILONERROR' => false,
                          'CURLOPT_RETURNTRANSFER' => true,
                          'CURLOPT_CONNECTTIMEOUT' => 60);

        $this->safelyArrayMerge(static::CONFIG_SECTION_CURL, $defaults, $configs);

        HttpHandler::$config = $defaults;
    }

    /**
     * Load configuration for curl.
     *
     * @param array $configs
     */
    private function loadHttp (array &$configs)
    {
        $defaults = array('exceptions' => true);

        $this->safelyArrayMerge(static::CONFIG_SECTION_HTTP, $defaults, $configs);

        HttpHandler::$throw = $defaults['exceptions'];
    }



    /**
     * Helper Methods
     */

    /**
     * Safely merge arrays.
     * @param array $a
     * @param array $b
     * @param void
     */
    private function safelyArrayMerge ($key, array &$a, array &$b) {

        if (isset($b[$key])) {

            $a = array_merge($a, $b[$key]);
            unset($b[$key]);

        }

    }
}
