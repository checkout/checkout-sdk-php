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

final class CheckoutApi extends CheckoutApmApi
{
    private TokensClient $tokensClient;
    private CustomersClient $customersClient;
    private PaymentsClient $paymentsClient;
    private InstrumentsClient $instrumentsClient;
    private ForexClient $forexClient;
    private DisputesClient $disputesClient;
    private SessionsClient $sessionsClient;
    private MarketplaceClient $marketplaceClient;
    private HostedPaymentsClient $hostedPaymentsClient;
    private PaymentLinksClient $paymentLinksClient;
    private RiskClient $riskClient;

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
        $this->marketplaceClient = new MarketplaceClient($baseApiClient,
            $this->getFilesApiClient($configuration),
            $this->getTransfersApiClient($configuration),
            $configuration);
    }

    public function getTokensClient(): TokensClient
    {
        return $this->tokensClient;
    }

    public function getCustomersClient(): CustomersClient
    {
        return $this->customersClient;
    }

    public function getPaymentsClient(): PaymentsClient
    {
        return $this->paymentsClient;
    }

    public function getInstrumentsClient(): InstrumentsClient
    {
        return $this->instrumentsClient;
    }

    public function getForexClient(): ForexClient
    {
        return $this->forexClient;
    }

    public function getDisputesClient(): DisputesClient
    {
        return $this->disputesClient;
    }

    public function getSessionsClient(): SessionsClient
    {
        return $this->sessionsClient;
    }

    public function getMarketplaceClient(): MarketplaceClient
    {
        return $this->marketplaceClient;
    }

    public function getHostedPaymentsClient(): HostedPaymentsClient
    {
        return $this->hostedPaymentsClient;
    }

    public function getPaymentLinksClient(): PaymentLinksClient
    {
        return $this->paymentLinksClient;
    }

    public function getRiskClient(): RiskClient
    {
        return $this->riskClient;
    }

    private function getBaseApiClient(CheckoutConfiguration $configuration): ApiClient
    {
        return new ApiClient($configuration, $configuration->getEnvironment()->getBaseUri());
    }

    private function getFilesApiClient(CheckoutConfiguration $configuration): ApiClient
    {
        return new ApiClient($configuration, $configuration->getEnvironment()->getFilesBaseUri());
    }

    private function getTransfersApiClient(CheckoutConfiguration $configuration): ApiClient
    {
        return new ApiClient($configuration, $configuration->getEnvironment()->getTransfersUri());
    }

}
