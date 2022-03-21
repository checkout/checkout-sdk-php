<?php

namespace Checkout;

final class AuthorizationType
{
    public static $publicKey = "public_key";
    public static $secretKey = "secret_key";
    public static $secretKeyOrOAuth = "secret_key_oauth";
    public static $publicKeyOrOAuth = "public_key_oauth";
    public static $oAuth = "oauth";
    public static $custom = "custom";

}
