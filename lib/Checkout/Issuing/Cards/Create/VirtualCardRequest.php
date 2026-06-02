<?php

namespace Checkout\Issuing\Cards\Create;

use Checkout\Issuing\CardType;

class VirtualCardRequest extends CardRequest
{
    public function __construct()
    {
        parent::__construct(CardType::$virtual);
    }

    /**
     * @var boolean
     */
    public $is_single_use;

    /**
     * @var array
     */
    public $return_credentials;

    /**
     * @var array
     */
    public $controls;

    /**
     * @var array
     */
    public $control_profiles;
}
