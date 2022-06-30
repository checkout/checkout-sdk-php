<?php

namespace Checkout\Sessions\Channel;

class AppSession extends ChannelData
{

    public function __construct()
    {
        parent::__construct(ChannelType::$app);
    }

    /**
     * @var string
     */
    public $sdk_app_id;

    /**
     * @var int
     */
    public $sdk_max_timeout;

    /**
     * @var SdkEphemeralPublicKey
     */
    public $sdk_ephem_pub_key;

    /**
     * @var string
     */
    public $sdk_reference_number;

    /**
     * @var string
     */
    public $sdk_encrypted_data;

    /**
     * @var string
     */
    public $sdk_transaction_id;

    /**
     * @var string value of SdkInterfaceType
     */
    public $sdk_interface_type;

    /**
     * @var array of UIElements
     */
    public $sdk_ui_elements;
}
