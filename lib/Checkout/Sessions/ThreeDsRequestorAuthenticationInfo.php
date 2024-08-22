<?php

namespace Checkout\Sessions;

use DateTime;

final class ThreeDsRequestorAuthenticationInfo
{
    /**
     * @var string value of ThreeDsReqAuthMethodType
     */
    public $three_ds_req_auth_method;

    /**
     * @var DateTime
     */
    public $three_ds_req_auth_timestamp;

    /**
     * @var string
     */
    public $three_ds_req_auth_data;
}
