<?php

namespace Checkout\Sessions\Channel;

class AppSession extends ChannelData
{

    public function __construct()
    {
        parent::__construct(ChannelType::$app);
    }

    public $sdk_app_id;

    public $sdk_max_timeout;

    // SdkEphemeralPublicKey
    public $sdk_ephem_pub_key;

    public $sdk_reference_number;

    public $sdk_encrypted_data;

    public $sdk_transaction_id;

    public $sdk_interface_type;

    public $sdk_ui_elements;

}
