<?php

namespace Checkout\Payments;

class Network
{
    /**
     * @var string
     */
    public $ipv4;

    /**
     * @var string
     */
    public $ipv6;

    /**
     * @var bool
     */
    public $tor;

    /**
     * @var bool
     */
    public $vpn;

    /**
     * @var bool
     */
    public $proxy;
}
