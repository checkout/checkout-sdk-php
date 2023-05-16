<?php

namespace Checkout\Issuing\Cards\Enrollment;

use Checkout\Common\Phone;

class UpdateThreeDSEnrollmentRequest
{
    /**
     * @var SecurityPair
     */
    public $security_pair;

    /**
     * @var string
     */
    public $password;

    /**
     * @var string
     */
    public $locale;

    /**
     * @var Phone
     */
    public $phone_number;
}
