<?php

namespace Checkout\Payments;

use Checkout\Common\ChallengeIndicatorType;
use Checkout\Common\Exemption;
use DateTime;

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

    // Only available in Four

    /**
     * @var string
     */
    public $status;

    /**
     * @var datetime
     */
    public $authentication_date;

    /**
     * @var int
     */
    public $authentication_amount;

    /**
     * @var string
     */
    public $flow_type;

    /**
     * @var string
     */
    public $status_reason_code;

    /**
     * @var string
     */
    public $challenge_cancel_reason;

    /**
     * @var string
     */
    public $score;

    /**
     * @var string
     */
    public $cryptogram_algorithm;
}
