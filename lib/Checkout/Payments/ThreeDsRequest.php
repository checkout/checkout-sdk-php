<?php

namespace Checkout\Payments;

class ThreeDsRequest
{
    public bool $enabled = true;

    public bool $attempt_n3d;

    public string $eci;

    public string $cryptogram;

    public string $xid;

    public string $version;

    public string $exemption;

    public string $challenge_indicator;

}
