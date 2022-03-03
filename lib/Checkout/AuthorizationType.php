<?php

namespace Checkout;

final class AuthorizationType
{
    public static string $publicKey = "public_key";
    public static string $secretKey = "secret_key";
    public static string $secretKeyOrOAuth = "secret_key_oauth";
    public static string $publicKeyOrOAuth = "public_key_oauth";
    public static string $oAuth = "oauth";
    public static string $custom = "custom";

}
