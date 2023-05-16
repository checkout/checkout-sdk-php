<?php

namespace Checkout\Issuing\Cards\Enrollment;

class SecurityQuestionThreeDSEnrollmentRequest extends ThreeDSEnrollmentRequest
{
    /**
     * @var SecurityPair
     */
    public $security_pair;
}
