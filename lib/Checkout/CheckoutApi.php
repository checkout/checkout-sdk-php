<?php

namespace Checkout;

use Checkout\Accounts\AccountsClient;
use Checkout\Balances\BalancesClient;
use Checkout\Customers\CustomersClient;
use Checkout\Disputes\DisputesClient;
use Checkout\Financial\FinancialClient;
use Checkout\Forex\ForexClient;
use Checkout\Instruments\InstrumentsClient;
use Checkout\Issuing\IssuingClient;
use Checkout\Metadata\MetadataClient;
use Checkout\Payments\PaymentsClient;
use Checkout\Payments\Hosted\HostedPaymentsClient;
use Checkout\Payments\Links\PaymentLinksClient;
use Checkout\Reports\ReportsClient;
use Checkout\Risk\RiskClient;
use Checkout\Sessions\SessionsClient;
use Checkout\Tokens\TokensClient;
use Checkout\Transfers\TransfersClient;
use Checkout\Workflows\WorkflowsClient;

final class CheckoutApi extends CheckoutApmApi
{
    private $tokensClient;
    private $customersClient;
    private $paymentsClient;
    private $instrumentsClient;
    private $forexClient;
    private $disputesClient;
    private $sessionsClient;
    private $accountsClient;
    private $hostedPaymentsClient;
    private $paymentLinksClient;
    private $riskClient;
    private $workflowsClient;
    private $balancesClient;
    private $transfersClient;
    private $reportsClient;
    private $metadataClient;
    private $financialClient;
    private $issuingClient;

    public function __construct(CheckoutConfiguration $configuration)
    {
        $baseApiClient = $this->getBaseApiClient($configuration);
        parent::__construct($baseApiClient, $configuration);
        $this->tokensClient = new TokensClient($baseApiClient, $configuration);
        $this->customersClient = new CustomersClient($baseApiClient, $configuration, AuthorizationType::$secretKeyOrOAuth);
        $this->paymentsClient = new PaymentsClient($baseApiClient, $configuration);
        $this->instrumentsClient = new InstrumentsClient($baseApiClient, $configuration);
        $this->forexClient = new ForexClient($baseApiClient, $configuration);
        $this->disputesClient = new DisputesClient($baseApiClient, $configuration, AuthorizationType::$secretKeyOrOAuth);
        $this->sessionsClient = new SessionsClient($baseApiClient, $configuration);
        $this->hostedPaymentsClient = new HostedPaymentsClient($baseApiClient, $configuration);
        $this->paymentLinksClient = new PaymentLinksClient($baseApiClient, $configuration);
        $this->riskClient = new RiskClient($baseApiClient, $configuration);
        $this->workflowsClient = new WorkflowsClient($baseApiClient, $configuration);
        $this->reportsClient = new ReportsClient($baseApiClient, $configuration);
        $this->metadataClient = new MetadataClient($baseApiClient, $configuration);
        $this->financialClient = new FinancialClient($baseApiClient, $configuration);
        $this->issuingClient = new IssuingClient($baseApiClient, $configuration);
        $this->balancesClient = new BalancesClient(
            $this->getBalancesApiClient($configuration),
            $configuration
        );
        $this->transfersClient = new TransfersClient(
            $this->getTransfersApiClient($configuration),
            $configuration
        );
        $this->accountsClient = new AccountsClient(
            $baseApiClient,
            $this->getFilesApiClient($configuration),
            $configuration
        );
    }

    /**
     * @return TokensClient
     */
    public function getTokensClient()
    {
        return $this->tokensClient;
    }

    /**
     * @return CustomersClient
     */
    public function getCustomersClient()
    {
        return $this->customersClient;
    }

    /**
     * @return PaymentsClient
     */
    public function getPaymentsClient()
    {
        return $this->paymentsClient;
    }

    /**
     * @return InstrumentsClient
     */
    public function getInstrumentsClient()
    {
        return $this->instrumentsClient;
    }

    /**
     * @return ForexClient
     */
    public function getForexClient()
    {
        return $this->forexClient;
    }

    /**
     * @return DisputesClient
     */
    public function getDisputesClient()
    {
        return $this->disputesClient;
    }

    /**
     * @return SessionsClient
     */
    public function getSessionsClient()
    {
        return $this->sessionsClient;
    }

    /**
     * @return AccountsClient
     */
    public function getAccountsClient()
    {
        return $this->accountsClient;
    }

    /**
     * @return HostedPaymentsClient
     */
    public function getHostedPaymentsClient()
    {
        return $this->hostedPaymentsClient;
    }

    /**
     * @return PaymentLinksClient
     */
    public function getPaymentLinksClient()
    {
        return $this->paymentLinksClient;
    }

    /**
     * @return RiskClient
     */
    public function getRiskClient()
    {
        return $this->riskClient;
    }

    /**
     * @return WorkflowsClient
     */
    public function getWorkflowsClient()
    {
        return $this->workflowsClient;
    }

    /**
     * @return ReportsClient
     */
    public function getReportsClient()
    {
        return $this->reportsClient;
    }

    /**
     * @return BalancesClient
     */
    public function getBalancesClient()
    {
        return $this->balancesClient;
    }

    /**
     * @return TransfersClient
     */
    public function getTransfersClient()
    {
        return $this->transfersClient;
    }

    /**
     * @return MetadataClient
     */
    public function getMetadataClient()
    {
        return $this->metadataClient;
    }

    /**
     * @return FinancialClient
     */
    public function getFinancialClient()
    {
        return $this->financialClient;
    }

    /**
     * @return IssuingClient
     */
    public function getIssuingClient()
    {
        return $this->issuingClient;
    }

    /**
     * @param CheckoutConfiguration $configuration
     * @return ApiClient
     */
    private function getBaseApiClient(CheckoutConfiguration $configuration)
    {
        return new ApiClient($configuration, $configuration->getEnvironment()->getBaseUri());
    }

    /**
     * @param CheckoutConfiguration $configuration
     * @return ApiClient
     */
    private function getFilesApiClient(CheckoutConfiguration $configuration)
    {
        return new ApiClient($configuration, $configuration->getEnvironment()->getFilesBaseUri());
    }

    /**
     * @param CheckoutConfiguration $configuration
     * @return ApiClient
     */
    private function getTransfersApiClient(CheckoutConfiguration $configuration)
    {
        return new ApiClient($configuration, $configuration->getEnvironment()->getTransfersUri());
    }

    /**
     * @param CheckoutConfiguration $configuration
     * @return ApiClient
     */
    private function getBalancesApiClient(CheckoutConfiguration $configuration)
    {
        return new ApiClient($configuration, $configuration->getEnvironment()->getBalancesUri());
    }
}
