<?php

namespace Checkout;

use DateTime;

class OAuthAccessToken
{

    private string $token;
    private ?DateTime $expirationDate;

    public function __construct(string $token, DateTime $expirationDate)
    {
        $this->token = $token;
        $this->expirationDate = $expirationDate;
    }

    public function isValid(): bool
    {
        if (is_null($this->expirationDate)) {
            return false;
        }
        return $this->expirationDate > new DateTime();
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getExpirationDate(): DateTime
    {
        return $this->expirationDate;
    }

}
