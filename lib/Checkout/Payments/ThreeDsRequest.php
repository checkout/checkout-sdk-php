<?php

namespace Checkout\Payments;

use Checkout\Common\ChallengeIndicatorType;
use Checkout\Common\Exemption;

class ThreeDsRequest
{
    /**
     * @var bool
     */
    public $enabled = true;

    /**
     * @var bool
     */
    public $attempt_n3d;

    /**
     * @var string
     */
    public $eci;

    /**
     * @var string
     */
    public $cryptogram;

    /**
     * @var string
     */
    public $xid;

    /**
     * @var string
     */
    public $version;

    /**
     * @var Exemption
     */
    public $exemption;

    /**
     * @var ChallengeIndicatorType
     */
    public $challenge_indicator;
}
