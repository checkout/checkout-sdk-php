<?php

namespace Checkout\Accounts;

class AgreedTerms
{
    /**
     * @var string the date the terms were agreed, in ISO 8601 format
     */
    public $date;

    /**
     * @var string the IP address from which the terms were agreed
     */
    public $ip_address;

    /**
     * @var string the name of the person who agreed to the terms
     */
    public $name;

    /**
     * @var string the email of the person who agreed to the terms
     */
    public $email;

    /**
     * @var string the version of the terms that were agreed
     */
    public $version;
}
