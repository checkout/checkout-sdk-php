<?php

namespace Checkout\Issuing\Cards\Enrollment;

use Checkout\Common\Phone;

abstract class ThreeDSEnrollmentRequest
{
    /**
     * @var string
     */
    public $locale;

    /**
     * @var Phone
     */
    public $phone_number;
}
