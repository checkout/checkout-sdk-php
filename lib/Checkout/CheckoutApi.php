<?php

namespace Checkout;

use Checkout\Apm\CheckoutApmApi;
use Checkout\Customers\CustomersClient;
use Checkout\Disputes\DisputesClient;
use Checkout\Events\EventsClient;
use Checkout\Instruments\InstrumentsClient;
use Checkout\Payments\Hosted\HostedPaymentsClient;
use Checkout\Payments\Links\PaymentLinksClient;
use Checkout\Payments\PaymentsClient;
use Checkout\Reconciliation\ReconciliationClient;
use Checkout\Risk\RiskClient;
use Checkout\Sources\SourcesClient;
use Checkout\Tokens\TokensClient;
use Checkout\Webhooks\WebhooksClient;

final class CheckoutApi extends CheckoutApmApi
{
    private $sourcesClient;
    private $tokensClient;
    private $instrumentsClient;
    private $webhooksClient;
    private $eventsClient;
    private $paymentsClient;
    private $customersClient;
    private $disputesClient;
    private $paymentLinksClient;
    private $hostedPaymentsClient;
    private $riskClient;
    private $reconciliationClient;

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration);
        $this->sourcesClient = new SourcesClient($apiClient, $configuration);
        $this->tokensClient = new TokensClient($apiClient, $configuration);
        $this->instrumentsClient = new InstrumentsClient($apiClient, $configuration);
        $this->webhooksClient = new WebhooksClient($apiClient, $configuration);
        $this->eventsClient = new EventsClient($apiClient, $configuration);
        $this->paymentsClient = new PaymentsClient($apiClient, $configuration);
        $this->customersClient = new CustomersClient($apiClient, $configuration);
        $this->disputesClient = new DisputesClient($apiClient, $configuration);
        $this->paymentLinksClient = new PaymentLinksClient($apiClient, $configuration);
        $this->hostedPaymentsClient = new HostedPaymentsClient($apiClient, $configuration);
        $this->riskClient = new RiskClient($apiClient, $configuration);
        $this->reconciliationClient = new ReconciliationClient($apiClient, $configuration);
    }

    /**
     * @return SourcesClient
     */
    public function getSourcesClient()
    {
        return $this->sourcesClient;
    }

    /**
     * @return TokensClient
     */
    public function getTokensClient()
    {
        return $this->tokensClient;
    }

    /**
     * @return InstrumentsClient
     */
    public function getInstrumentsClient()
    {
        return $this->instrumentsClient;
    }

    /**
     * @return WebhooksClient
     */
    public function getWebhooksClient()
    {
        return $this->webhooksClient;
    }

    /**
     * @return EventsClient
     */
    public function getEventsClient()
    {
        return $this->eventsClient;
    }

    /**
     * @return PaymentsClient
     */
    public function getPaymentsClient()
    {
        return $this->paymentsClient;
    }

    /**
     * @return CustomersClient
     */
    public function getCustomersClient()
    {
        return $this->customersClient;
    }

    /**
     * @return DisputesClient
     */
    public function getDisputesClient()
    {
        return $this->disputesClient;
    }

    /**
     * @return PaymentLinksClient
     */
    public function getPaymentLinksClient()
    {
        return $this->paymentLinksClient;
    }

    /**
     * @return HostedPaymentsClient
     */
    public function getHostedPaymentsClient()
    {
        return $this->hostedPaymentsClient;
    }

    /**
     * @return RiskClient
     */
    public function getRiskClient()
    {
        return $this->riskClient;
    }

    /**
     * @return ReconciliationClient
     */
    public function getReconciliationClient()
    {
        return $this->reconciliationClient;
    }
}
