<?php

namespace Checkout\Payments;

class ThreeDsRequest
{
    public $enabled = true;

    public $attempt_n3d;

    public $eci;

    public $cryptogram;

    public $xid;

    public $version;

    public $exemption;

    public $challenge_indicator;

}
