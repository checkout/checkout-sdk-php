<?php

namespace Checkout\Sessions;

final class ThreeDsReqAuthMethodType
{
    public static $federated_id = "federated_id";
    public static $fido_authenticator = "fido_authenticator";
    public static $fido_authenticator_fido_assurance_data_signed = "fido_authenticator_fido_assurance_data_signed";
    public static $issuer_credentials = "issuer_credentials";
    public static $no_threeds_requestor_authentication_occurred = "no_threeds_requestor_authentication_occurred";
    public static $src_assurance_data = "src_assurance_data";
    public static $three3ds_requestor_own_credentials = "three3ds_requestor_own_credentials";
    public static $third_party_authentication = "third_party_authentication";
}
