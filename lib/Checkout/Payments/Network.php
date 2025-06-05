<?php

namespace Checkout\Payments;

class Network
{
    /**
     * The device's IPV4 address. Not required if you provide the ipv6 field (Optional)
     *
     * @var string|null
     */
    public $ipv4;

    /**
     * The device's IPV6 address. Not required if you provide the ipv4 field (Optional)
     *
     * @var string|null
     */
    public $ipv6;

    /**
     * Specifies if the Tor network was used in the browser session (Optional)
     *
     * @var bool|null
     */
    public $tor;

    /**
     * Specifies if a virtual private network (VPN) was used in the browser session (Optional)
     *
     * @var bool|null
     */
    public $vpn;

    /**
     * Specifies if a proxy was used in the browser session (Optional)
     *
     * @var bool|null
     */
    public $proxy;
}
