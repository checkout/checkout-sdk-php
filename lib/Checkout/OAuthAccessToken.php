<?php

namespace Checkout;

use DateTime;

class OAuthAccessToken
{
    private $token;

    private $tokenType;

    private $expirationDate;

    /**
     * @param string $token
     * @param string $tokenType
     * @param DateTime $expirationDate
     */
    public function __construct($token, $tokenType, DateTime $expirationDate)
    {
        $this->token = $token;
        $this->tokenType = $tokenType;
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
     * @return string
     */
    public function getTokenType()
    {
        return $this->tokenType;
    }

    /**
     * @return DateTime
     */
    public function getExpirationDate()
    {
        return $this->expirationDate;
    }
}
