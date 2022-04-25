<?php

namespace Checkout\Four;

use Checkout\ApiClient;
use Checkout\CheckoutConfiguration;
use Checkout\Customers\Four\CustomersClient;
use Checkout\Disputes\DisputesClient;
use Checkout\Forex\ForexClient;
use Checkout\Instruments\Four\InstrumentsClient;
use Checkout\Marketplace\MarketplaceClient;
use Checkout\Payments\Four\PaymentsClient;
use Checkout\Payments\Hosted\HostedPaymentsClient;
use Checkout\Payments\Links\PaymentLinksClient;
use Checkout\Risk\RiskClient;
use Checkout\Sessions\SessionsClient;
use Checkout\Tokens\TokensClient;
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
    private $marketplaceClient;
    private $hostedPaymentsClient;
    private $paymentLinksClient;
    private $riskClient;
    private $workflowsClient;

    public function __construct(CheckoutConfiguration $configuration)
    {
        $baseApiClient = $this->getBaseApiClient($configuration);
        parent::__construct($baseApiClient, $configuration);
        $this->tokensClient = new TokensClient($baseApiClient, $configuration);
        $this->customersClient = new CustomersClient($baseApiClient, $configuration);
        $this->paymentsClient = new PaymentsClient($baseApiClient, $configuration);
        $this->instrumentsClient = new InstrumentsClient($baseApiClient, $configuration);
        $this->forexClient = new ForexClient($baseApiClient, $configuration);
        $this->disputesClient = new DisputesClient($baseApiClient, $configuration);
        $this->sessionsClient = new SessionsClient($baseApiClient, $configuration);
        $this->hostedPaymentsClient = new HostedPaymentsClient($baseApiClient, $configuration);
        $this->paymentLinksClient = new PaymentLinksClient($baseApiClient, $configuration);
        $this->riskClient = new RiskClient($baseApiClient, $configuration);
        $this->workflowsClient = new WorkflowsClient($baseApiClient, $configuration);
        $this->marketplaceClient = new MarketplaceClient(
            $baseApiClient,
            $this->getFilesApiClient($configuration),
            $this->getTransfersApiClient($configuration),
            $this->getBalancesApiClient($configuration),
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
     * @return MarketplaceClient
     */
    public function getMarketplaceClient()
    {
        return $this->marketplaceClient;
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
