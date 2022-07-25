<?php

namespace Checkout;

final class SdkAuthorization
{
    const BEARER = "Bearer ";

    private $platformType;

    private $credential;

    /**
     * @param mixed $platformType
     * @param mixed $credential
     */
    public function __construct($platformType, $credential)
    {
        $this->platformType = $platformType;
        $this->credential = $credential;
    }

    /**
     * @throws CheckoutAuthorizationException
     */
    public function getAuthorizationHeader()
    {
        switch ($this->platformType) {
            case PlatformType::$previous:
            case PlatformType::$custom:
                return $this->credential;
            case PlatformType::$default:
            case PlatformType::$default_oauth:
                return self::BEARER . $this->credential;
            default:
                throw new CheckoutAuthorizationException("Invalid platform type");
        }
    }
}
