<?php

namespace Checkout\Sessions;

class AuthenticationMethod
{
    public static $no_authentication = "no_authentication";
    public static $own_credentials = "own_credentials";
    public static $federated_id = "federated_id";
    public static $issuer_credentials = "issuer_credentials";
    public static $third_party_authentication = "third_party_authentication";
    public static $fido = "fido";
}
