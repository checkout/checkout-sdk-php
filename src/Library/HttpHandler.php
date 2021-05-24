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

use Checkout\CheckoutApi;
use Checkout\Library\Exceptions\CheckoutHttpException;

/**
 * Http handler class.
 *
 * @category SDK
 * @package  Checkout.com
 * @author   Platforms Development Team <platforms@checkout.com>
 * @license  https://opensource.org/licenses/mit-license.html MIT License
 * @link     https://docs.checkout.com/
 */
class HttpHandler
{

    /**
     * Name of the module used.
     *
     * @var string
     */
    const ADAPTER_NAME = 'curl';

    /**
     * Authentication type public.
     *
     * @var integer
     */
    const AUTH_TYPE_PUBLIC = 0;

    /**
     * Authentication type secret.
     *
     * @var integer
     */
    const AUTH_TYPE_SECRET = 1;

    /**
     * Authentication types.
     *
     * @var array
     */
    const AUTH_TYPES = array(HttpHandler::AUTH_TYPE_PUBLIC => 'PUBLIC KEY',
                             HttpHandler::AUTH_TYPE_SECRET => 'SECRET KEY');

    /**
     * Content type JSON.
     *
     * @var string
     */
    const MIME_TYPE_JSON = 'application/json; charset=utf-8';

    /**
     * Content type text.
     *
     * @var string
     */
    const MIME_TYPE_HTML = 'text/html; charset=utf-8';

    /**
     * Method post.
     *
     * @var string
     */
    const METHOD_POST = 'POST';

    /**
     * Method get.
     *
     * @var string
     */
    const METHOD_GET = 'GET';

    /**
     * Method put.
     *
     * @var string
     */
    const METHOD_PUT = 'PUT';

    /**
     * Method patch.
     *
     * @var string
     */
    const METHOD_PATCH = 'PATCH';

    /**
     * Method delete.
     *
     * @var string
     */
    const METHOD_DEL = 'DELETE';

    /**
     * SKD Domain.
     *
     * @var string
     */
    const TARGET_DOMAIN = 'https://{env}.checkout.com/';

    /**
     * Client mode. Execute.
     *
     * @var string
     */
    const MODE_EXECUTE = 1;

    /**
     * Client mode. Simulate request.
     *
     * @var string
     */
    const MODE_RETRIEVE = 0;

    /**
     * Client mode. Simulate request.
     *
     * @var string
     */
    const HTTP_CODE = 'http_code';

    /**
     * Target URL.
     *
     * @var string
     */
    protected $url;

    /**
     * Auth type.
     *
     * @var integer
     */
    protected $auth;

    /**
     * Request body
     *
     * @var mixex
     */
    protected $body = null;

    /**
     * Request body
     *
     * @var mixex
     */
    protected $response = null;

    /**
     * List of headers.
     *
     * @var string
     */
    protected $headers = array();

    /**
     * List of headers.
     *
     * @var string
     */
    protected $options = array();

    /**
     * Content type.
     *
     * @var string
     */
    protected $contentType = 'Content-type: ' . HttpHandler::MIME_TYPE_JSON;

    /**
     * Target channel.
     *
     * @var CheckoutConfiguration
     */
    protected $configuration;

    /**
     * Request method.
     *
     * @var string
     */
    protected $method = HttpHandler::METHOD_GET;

    /**
     * Query parameters.
     *
     * @var array
     */
    protected $params = array();

    /**
     * Info of the latest request.
     *
     * @var array
     */
    protected $info = array();

    /**
     * Info of the error.
     *
     * @var array
     */
    protected $error = array();

    /**
     * Curl options.
     *
     * @var array
     */
    public static $config;

    /**
     * Throw exceptions.
     *
     * @var bool
     */
    public static $throw = true;


    /**
     * Methods
     */

    /**
     * Create new instance of the model.
     *
     * @param  string $url
     * @param  int    $auth
     * @return self new static
     */
    public static function create($url = '', $auth = HttpHandler::AUTH_TYPE_SECRET)
    {
        return new static($url, $auth);
    }


    /**
     * Magic Methods
     */

    /**
     * Verify if adapter is available before instantiate the class.
     *
     * @param  string $url
     * @param  int    $auth
     * @throws CheckoutHttpException
     */
    public function __construct($url = '', $auth = HttpHandler::AUTH_TYPE_SECRET)
    {
        $this->url = $url;
        $this->auth = $auth;
    }


    /**
     * Methods
     */

    /**
     * Execute the request.
     *
     * @return self
     */
    public function execute()
    {
        $url = $this->getURL();
        $curl = curl_init($url . $this->getQueryParameters(true));

        // Add custom options
        $this->setUpCurl($curl);

        LogHandler::request($this->method . ' ' . $url . ' ' . static::AUTH_TYPES[$this->auth]);
        $this->response = curl_exec($curl); // contains the body string
        $this->info = curl_getinfo($curl);

        $ex = $this->handleError($curl);
        if (static::$throw && $ex) {
            throw $ex;
        }

        LogHandler::response($this->method . ' ' . $url . ' (Code: ' . $this->info[static::HTTP_CODE] . ')');

        // Close cURL resource to free up system resources
        curl_close($curl);
        return $this;
    }


    /**
     * Handle errors from the response.
     *
     * @param resource $curl
     * @return CheckoutHttpException
     */
    protected function handleError($curl)
    {
        $ex = null;
        $errno = curl_errno($curl);
        if ($errno) {
            $error = curl_error($curl);
            $this->error = array($errno => $errno);
            $ex = (new CheckoutHttpException($error, $errno));
        }

        $code = $this->getCode();
        if ($code >= 400) {
            $ex = (new CheckoutHttpException('The endpoint did not accept the request.', $code))->setBody($this->response);
        }

        return $ex;
    }

    /**
     * Add options to curl resource.
     *
     * @param  resouce $curl
     * @return array
     */
    protected function setUpCurl($curl)
    {
        $options = $this->options() + $this->options;

        foreach ($options as $option => $value) {
            curl_setopt($curl, $option, $value);
        }

        // Set Body
        if (in_array($this->method, array(static::METHOD_POST, static::METHOD_PUT, static::METHOD_PATCH))) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $this->body);
        }
    }

    /**
     * Fixed options
     * @return array
     */
    protected function options()
    {
        $options = array();

        foreach (static::$config as $key => $value) {
            $const = constant($key);
            if ($const) {
                $options[$const] = $value;
            }
        }

        $options[CURLOPT_HTTPHEADER] = $this->getHeaders();
        $options[CURLOPT_CUSTOMREQUEST] = $this->method;

        return $options;
    }

    /**
     * Fixed headers
     *
     * @return array
     */
    protected function headers()
    {
        return array($this->contentType,
                     'Accept: ' . static::MIME_TYPE_JSON,
                     'Authorization: ' . $this->getKey(),
                     'User-Agent: checkout-sdk-php/' . CheckoutApi::VERSION);
    }

    /**
     * Serialise HTTP client.
     *
     * @return array
     */
    public function serialize()
    {
        return array('url'      => $this->getUrl() . $this->getQueryParameters(true),
                     'header'   => $this->getHeaders(),
                     'method'   => $this->method,
                     'body'     => $this->body);
    }


    /**
     * Setters and Getters
     */

    /**
     * Get list of headers.
     *
     * @return array
     */
    public function getHeaders()
    {
        return array_merge($this->headers, $this->headers());
    }

    /**
     * Set URL.
     *
     * @param  string $url
     * @return self $this
     */
    public function setURL($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Set auth.
     *
     * @param  integer $auth
     * @return self $this
     */
    public function setAuth($auth = HttpHandler::AUTH_TYPE_SECRET)
    {
        $this->auth = $auth;
        return $this;
    }

    /**
     * Get auth.
     *
     * @return integer $this
     */
    public function getAuth()
    {
        return $this->auth;
    }

    /**
     * Set body string/array type only.
     *
     * @param  mixed $body
     * @return self $this
     */
    public function setBody($body)
    {
        if (is_array($body) || $body instanceof Model) {
            $body = json_encode($body, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        }

        $this->addHeader('Content-length: ' . strlen($body));
        $this->body = $body;

        if ($this->method === static::METHOD_GET) {
            $this->method = static::METHOD_POST;
        }

        return $this;
    }

    /**
     * Get body as it is.
     *
     * @return mixed $this->body
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set content type.
     *
     * @param  integer $type
     * @return self $this
     */
    public function setContentType($type = HttpHandler::MIME_TYPE_JSON)
    {
        $this->contentType = 'Content-type: ' . $type;
        return $this;
    }

    /**
     * Get content-type.
     *
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * Set method.
     *
     * @param  string $method
     * @return self $this
     */
    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * Get method.
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Add new header to the list.
     *
     * @param  string $header
     * @return self $this
     */
    public function addHeader($header)
    {
        $this->headers []= $header;
        return $this;
    }

    /**
     * Set custom cURL options.
     *
     * @param  integer $type
     * @return self $this
     */
    public function addOption($key, $value)
    {
        if (!Utilities::isEmpty($key)) {
            $this->options[$key] = $value;
        }

        return $this;
    }

    /**
     * Get authentication key.
     *
     * @return string $keys
     */
    public function getKey()
    {
        $key = '';
        if ($this->configuration) {
            $key = ($this->auth === static::AUTH_TYPE_PUBLIC ? $this->configuration->getPublicKey() : $this->configuration->getSecretKey());
        }

        return $key;
    }

    /**
     * Get URL.
     *
     * @return string $keys
     */
    public function getURL()
    {
        return str_replace('{env}', $this->configuration->getAPI(), static::TARGET_DOMAIN) . $this->url;
    }

    /**
     * Get response body.
     *
     * @return mixed $response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Set Channel.
     *
     * @return self $this
     */
    public function setConfiguration(CheckoutConfiguration $configuration, $auth = HttpHandler::AUTH_TYPE_SECRET)
    {
        $this->configuration = $configuration;
        $this->auth = $auth;
        return $this;
    }

    /**
     * Set query parameters.
     *
     * @param  array $params
     * @return self $this
     */
    public function setQueryParameters(array $params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * Get query parameters.
     *
     * @param  bool $query
     * @return array|string
     */
    public function getQueryParameters($query = false)
    {
        $result = '';
        if($query && $this->params) {
            $result = '?' . http_build_query($this->params);
        }
        
        if(!$query) {
            $result = $this->params;
        }

        return $result;
    }

    /**
     * Set Idempotency key.
     *
     * @note   Be cautious when using idempotency keys.
     *       If we detect concurrent requests with the same idempotency key,
     *       only one request will be processed and the other requests will return a 429 - Too Many Requests response.
     * @param  string $key
     * @return self $this
     */
    public function setIdempotencyKey($key = '')
    {
        if ($key) {
            $this->addHeader('Cko-Idempotency-Key: ' . $key);
        }

        return $this;
    }

    /**
     * Get query parameters.
     *
     * @return int
     */
    public function getCode()
    {
        $code = 0;
        if (isset($this->info[static::HTTP_CODE])) {
            $code = $this->info[static::HTTP_CODE];
        }

        return $code;
    }
}
