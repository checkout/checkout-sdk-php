<?php

namespace Checkout;

use DateTime;

class OAuthAccessToken
{

    private $token;
    private $expirationDate;

    /**
     * @param string $token
     * @param DateTime $expirationDate
     */
    public function __construct($token, DateTime $expirationDate)
    {
        $this->token = $token;
        $this->expirationDate = $expirationDate;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        if (is_null($this->expirationDate)) {
            return false;
        }
        return $this->expirationDate > new DateTime();
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @return DateTime
     */
    public function getExpirationDate()
    {
        return $this->expirationDate;
    }
}
