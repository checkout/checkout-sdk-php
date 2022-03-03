<?php

namespace Checkout;

class FourOAuthScope
{
    public static string $Vault = "vault";
    public static string $VaultInstruments = "vault:instruments";
    public static string $VaultTokenization = "vault:tokenization";
    public static string $Gateway = "gateway";
    public static string $GatewayPayment = "gateway:payment";
    public static string $GatewayPaymentDetails = "gateway:payment-details";
    public static string $GatewayPaymentAuthorization = "gateway:payment-authorizations";
    public static string $GatewayPaymentVoids = "gateway:payment-voids";
    public static string $GatewayPaymentCaptures = "gateway:payment-captures";
    public static string $GatewayPaymentRefunds = "gateway:payment-refunds";
    public static string $Fx = "fx";
    public static string $PayoutsBankDetails = "payouts:bank-details";
    public static string $Sessions = "sessions";
    public static string $SessionsApp = "sessions:app";
    public static string $SessionsBrowser = "sessions:browser";
    public static string $Disputes = "disputes";
    public static string $DisputesView = "disputes:view";
    public static string $DisputesProvideEvidence = "disputes:provide-evidence";
    public static string $DisputesAccept = "disputes:accept";
    public static string $Marketplace = "marketplace";
    public static string $Flow = "flow";
    public static string $FlowWorkflows = "flow:workflows";
    public static string $FlowEvents = "flow:events";
    public static string $Files = "files";
    public static string $FilesRetrieve = "files:retrieve";
    public static string $FilesUpload = "files:upload";
    public static string $IssuingClient = "issuing:client";
    public static string $IssuingPartner = "issuing:partner";
    public static string $Risk = "risk";
    public static string $RiskAssessment = "risk:assessment";
    public static string $RiskSettings = "risk:settings";
}
