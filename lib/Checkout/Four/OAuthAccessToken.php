<?php

namespace Checkout\Four;

use DateTime;

class OAuthAccessToken
{

    private $token;
    private $expirationDate;

    public function __construct($token, DateTime $expirationDate)
    {
        $this->token = $token;
        $this->expirationDate = $expirationDate;
    }

    public function isValid()
    {
        if (is_null($this->expirationDate)) {
            return false;
        }
        return $this->expirationDate > new DateTime();
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getExpirationDate()
    {
        return $this->expirationDate;
    }

}
