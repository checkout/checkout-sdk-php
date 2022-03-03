<?php

namespace Checkout\Sessions\Channel;

class AppSession extends ChannelData
{

    public function __construct()
    {
        parent::__construct(ChannelType::$app);
    }

    public string $sdk_app_id;

    public int $sdk_max_timeout;

    public SdkEphemeralPublicKey $sdk_ephem_pub_key;

    public string $sdk_reference_number;

    public string $sdk_encrypted_data;

    public string $sdk_transaction_id;

    public string $sdk_interface_type;

    public array $sdk_ui_elements;

}
