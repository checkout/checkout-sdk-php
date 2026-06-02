<?php

namespace Checkout\Sessions;

class GoogleSpa
{
    /**
     * Fully qualified URL for redirecting the user's browser session after authentication.
     * For example, this field may be the merchant's website for purchase confirmation once
     * payment is complete. Required if in full redirect (not iframe) mode.
     * [Optional]
     * @var string|null $continue_url
     */
    public $continue_url;
}
