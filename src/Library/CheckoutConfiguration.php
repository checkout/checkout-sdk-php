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

/**
 * Checkout (channel) configuration.
 *
 * @category SDK
 * @package  Checkout.com
 * @author   Platforms Development Team <platforms@checkout.com>
 * @license  https://opensource.org/licenses/mit-license.html MIT License
 * @link     https://docs.checkout.com/
 */
final class CheckoutConfiguration
{

    /**
     * Environment type.
     *
     * @var string
     */
    const ENVIRONMENT_SANDBOX = 'sandbox';

    /**
     * Sandbox environment domain.
     *
     * @var string
     */
    const ENVIRONMENT_SANDBOX_DOMAIN = 'api.sandbox';

    /**
     * Production environment domain.
     *
     * @var string
     */
    const ENVIRONMENT_PRODUCTION_DOMAIN = 'api';

    /**
     * Secret key.
     *
     * @var string
     */
    private $secret;

    /**
     * Public key.
     *
     * @var string
     */
    private $publicKey;

    /**
     * Sandbox environment.
     *
     * @var bool
     */
    private $sandbox;

    /**
     * Initialise channel object.
     *
     * @param string $secret
     * @param bool   $sandbox
     * @param string $public
     */
    public function __construct($secret, $sandbox = true, $public = '')
    {
        $this->secret = $secret;
        $this->sandbox = $sandbox;
        $this->publicKey = $public;
    }

    /**
     * Set methods
     */

    /**
     * Set secret key.
     *
     * @param  string $secret
     * @return self $this
     */
    public function setSecretKey($secret)
    {
        $this->secret = $secret;
        return $this;
    }

    /**
     * Set public key.
     *
     * @param  string $public
     * @return self $this
     */
    public function setPublicKey($public)
    {
        $this->publicKey = $public;
        return $this;
    }

    /**
     * Set sandbox environment.
     *
     * @param  bool $sandbox
     * @return self $this
     */
    public function setSandbox($sandbox)
    {
        $this->sandbox = $sandbox;
        return $this;
    }

    /**
     * Get secret key.
     *
     * @return string $secret
     */
    public function getSecretKey()
    {
        return $this->secret;
    }

    /**
     * Get public key.
     *
     * @return string $public
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * Get sandbox environment.
     *
     * @param bool $public
     */
    public function getSandbox()
    {
        return $this->sandbox;
    }

    /**
     * Get AOU.
     *
     * @param string $this->environment
     */
    public function getAPI()
    {
        return $this->sandbox ? static::ENVIRONMENT_SANDBOX_DOMAIN :
                                static::ENVIRONMENT_PRODUCTION_DOMAIN;
    }
}
