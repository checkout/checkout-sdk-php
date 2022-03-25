<?php

namespace Checkout\Four;

class FourOAuthScope
{
    public static $Vault = "vault";
    public static $VaultInstruments = "vault:instruments";
    public static $VaultTokenization = "vault:tokenization";
    public static $Gateway = "gateway";
    public static $GatewayPayment = "gateway:payment";
    public static $GatewayPaymentDetails = "gateway:payment-details";
    public static $GatewayPaymentAuthorization = "gateway:payment-authorizations";
    public static $GatewayPaymentVoids = "gateway:payment-voids";
    public static $GatewayPaymentCaptures = "gateway:payment-captures";
    public static $GatewayPaymentRefunds = "gateway:payment-refunds";
    public static $Fx = "fx";
    public static $PayoutsBankDetails = "payouts:bank-details";
    public static $Sessions = "sessions";
    public static $SessionsApp = "sessions:app";
    public static $SessionsBrowser = "sessions:browser";
    public static $Disputes = "disputes";
    public static $DisputesView = "disputes:view";
    public static $DisputesProvideEvidence = "disputes:provide-evidence";
    public static $DisputesAccept = "disputes:accept";
    public static $Marketplace = "marketplace";
    public static $Flow = "flow";
    public static $FlowWorkflows = "flow:workflows";
    public static $FlowEvents = "flow:events";
    public static $Files = "files";
    public static $FilesRetrieve = "files:retrieve";
    public static $FilesUpload = "files:upload";
    public static $IssuingClient = "issuing:client";
    public static $IssuingPartner = "issuing:partner";
    public static $Risk = "risk";
    public static $RiskAssessment = "risk:assessment";
    public static $RiskSettings = "risk:settings";
    public static $Transfers = "transfers";
    public static $TransfersCreate = "transfers:create";
    public static $TransfersView = "transfers:view";
    public static $Balances = "balances";
    public static $BalancesView = "balances:view";
}
