<?php

namespace Checkout;

class OAuthScope
{
    public static $Vault = "vault";
    public static $VaultInstruments = "vault:instruments";
    public static $VaultTokenization = "vault:tokenization";
    public static $VaultCardMetadata = "vault:card-metadata";
    public static $Gateway = "gateway";
    public static $GatewayPayment = "gateway:payment";
    public static $GatewayPaymentDetails = "gateway:payment-details";
    public static $GatewayPaymentAuthorization = "gateway:payment-authorizations";
    public static $GatewayPaymentVoids = "gateway:payment-voids";
    public static $GatewayPaymentCaptures = "gateway:payment-captures";
    public static $GatewayPaymentRefunds = "gateway:payment-refunds";
    public static $Fx = "fx";
    public static $PayoutsBankDetails = "payouts:bank-details";
    public static $SessionsApp = "sessions:app";
    public static $SessionsBrowser = "sessions:browser";
    public static $Disputes = "disputes";
    public static $DisputesView = "disputes:view";
    public static $DisputesProvideEvidence = "disputes:provide-evidence";
    public static $DisputesAccept = "disputes:accept";
    public static $Marketplace = "marketplace";
    public static $Accounts = "accounts";
    public static $Flow = "flow";
    public static $FlowWorkflows = "flow:workflows";
    public static $FlowEvents = "flow:events";
    public static $Files = "files";
    public static $FilesRetrieve = "files:retrieve";
    public static $FilesUpload = "files:upload";
    public static $FilesDownload = "files:download";
    public static $Transfers = "transfers";
    public static $TransfersCreate = "transfers:create";
    public static $TransfersView = "transfers:view";
    public static $Balances = "balances";
    public static $BalancesView = "balances:view";
    public static $Middleware = "middleware";
    public static $MiddlewareMerchantsSecret = "middleware:merchants-secret";
    public static $MiddlewareMerchantsPublic = "middleware:merchants-public";
    public static $Reports = "reports";
    public static $ReportsView = "reports:view";
    public static $FinancialActions = "financial-actions";
    public static $FinancialActionsView = "financial-actions:view";
    public static $issuingClient = "issuing:client";
    public static $issuingCardMgmt = "issuing:card-mgmt";
    public static $issuingControlsRead = "issuing:controls-read";
    public static $issuingControlsWrite = "issuing:controls-write";
}
