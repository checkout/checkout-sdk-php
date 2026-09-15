<?php

namespace Checkout;

/**
 * OAuth 2.0 client credentials scopes.
 *
 * Mirrors components.securitySchemes.OAuth.flows.clientCredentials.scopes in the Checkout.com API
 * specification, plus the scopes that appear only in per-operation security requirements and are
 * never declared in that map: compliance-requests, compliance-requests:read,
 * compliance-requests:respond, vault:gpayme-enrollment and vault:tokens-metadata.
 *
 * Five further properties -- issuing:card-mgmt, issuing:client, marketplace,
 * middleware:gateway and middleware:payment-context -- appear nowhere in the specification at
 * all, but the authorization server still grants them and callers still request them, so they are
 * kept for backward compatibility. Each is marked inline. Do not assume a scope is dead because
 * the specification omits it: the sandbox payouts client is provisioned for marketplace and
 * answers a request for accounts with invalid_scope.
 *
 * Properties are ordered alphabetically. Note that $PaymentContext and $GatewayPaymentContexts are
 * different scopes: the specification requires the former for GET /payment-contexts/{id} and the
 * latter for POST /payment-contexts. "Payment Context" is the only scope whose wire value contains a
 * space and a capital letter, which looks like a specification authoring defect; it is mirrored
 * verbatim regardless, because that is the value the authorization server is documented to accept.
 */
class OAuthScope
{
    public static $Accounts = "accounts";
    public static $AgenticInventory = "agentic:inventory";
    public static $Balances = "balances";
    public static $BalancesTopUpInstructions = "balances:top-up-instructions";
    public static $BalancesView = "balances:view";
    public static $CardManagement = "card-management";
    public static $ComplianceRequests = "compliance-requests";
    public static $ComplianceRequestsRead = "compliance-requests:read";
    public static $ComplianceRequestsRespond = "compliance-requests:respond";
    public static $Disputes = "disputes";
    public static $DisputesAccept = "disputes:accept";
    public static $DisputesProvideEvidence = "disputes:provide-evidence";
    public static $DisputesSchemeFiles = "disputes:scheme-files";
    public static $DisputesView = "disputes:view";
    public static $Files = "files";
    public static $FilesDownload = "files:download";
    public static $FilesRetrieve = "files:retrieve";
    public static $FilesUpload = "files:upload";
    public static $FinancialActions = "financial-actions";
    public static $FinancialActionsView = "financial-actions:view";
    public static $Flow = "flow";
    public static $FlowEvents = "flow:events";
    public static $FlowReflow = "flow:reflow";
    public static $FlowWorkflows = "flow:workflows";
    public static $Forward = "forward";
    public static $ForwardSecrets = "forward:secrets";
    public static $Fx = "fx";
    public static $Gateway = "gateway";
    public static $GatewayPayment = "gateway:payment";
    public static $GatewayPaymentAuthorization = "gateway:payment-authorizations";
    public static $GatewayPaymentCancellations = "gateway:payment-cancellations";
    public static $GatewayPaymentCaptures = "gateway:payment-captures";
    public static $GatewayPaymentContexts = "gateway:payment-contexts";
    public static $GatewayPaymentDetails = "gateway:payment-details";
    public static $GatewayPaymentRefunds = "gateway:payment-refunds";
    public static $GatewayPaymentVoids = "gateway:payment-voids";
    public static $IdentityVerification = "identity-verification";
    public static $IssuingCardManagementRead = "issuing:card-management-read";
    public static $IssuingCardManagementWrite = "issuing:card-management-write";
    public static $IssuingCardMgmt = "issuing:card-mgmt"; // not in spec; kept for backward compat
    public static $IssuingClient = "issuing:client"; // not in spec; kept for backward compat
    public static $IssuingControlsRead = "issuing:controls-read";
    public static $IssuingControlsWrite = "issuing:controls-write";
    public static $IssuingDisputes = "issuing-disputes";
    public static $IssuingDisputesRead = "issuing:disputes-read";
    public static $IssuingDisputesWrite = "issuing:disputes-write";
    public static $IssuingTransactionsRead = "issuing:transactions-read";
    public static $IssuingTransactionsWrite = "issuing:transactions-write";
    public static $Marketplace = "marketplace"; // not in spec; kept for backward compat
    public static $Middleware = "middleware";
    public static $MiddlewareGateway = "middleware:gateway"; // not in spec; kept for backward compat
    public static $MiddlewareMerchantsPublic = "middleware:merchants-public";
    public static $MiddlewareMerchantsSecret = "middleware:merchants-secret";
    public static $MiddlewarePaymentContext = "middleware:payment-context"; // not in spec; kept for backward compat
    public static $PaymentContext = "Payment Context";
    public static $PaymentSessions = "payment-sessions";
    public static $PaymentsSearch = "payments:search";
    public static $PayoutsBankDetails = "payouts:bank-details";
    public static $Reports = "reports";
    public static $ReportsView = "reports:view";
    public static $SessionsApp = "sessions:app";
    public static $SessionsBrowser = "sessions:browser";
    public static $Transactions = "transactions";
    public static $Transfers = "transfers";
    public static $TransfersCreate = "transfers:create";
    public static $TransfersView = "transfers:view";
    public static $Vault = "vault";
    public static $VaultApmeEnrollment = "vault:apme-enrollment";
    public static $VaultCardMetadata = "vault:card-metadata";
    public static $VaultCustomers = "vault:customers";
    public static $VaultGpaymeEnrollment = "vault:gpayme-enrollment";
    public static $VaultInstruments = "vault:instruments";
    public static $VaultNetworkTokens = "vault:network-tokens";
    public static $VaultRealTimeAccountUpdater = "vault:real-time-account-updater";
    public static $VaultTokenization = "vault:tokenization";
    public static $VaultTokensMetadata = "vault:tokens-metadata";
}
