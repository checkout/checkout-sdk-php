<?php

namespace Checkout\Previous;

use Checkout\ApiClient;
use Checkout\Apm\CheckoutApmApi;
use Checkout\AuthorizationType;
use Checkout\CheckoutConfiguration;
use Checkout\Customers\CustomersClient;
use Checkout\Disputes\DisputesClient;
use Checkout\Events\Previous\EventsClient;
use Checkout\Instruments\Previous\InstrumentsClient;
use Checkout\Payments\Hosted\HostedPaymentsClient;
use Checkout\Payments\Links\PaymentLinksClient;
use Checkout\Payments\Previous\PaymentsClient;
use Checkout\Reconciliation\Previous\ReconciliationClient;
use Checkout\Risk\RiskClient;
use Checkout\Sources\Previous\SourcesClient;
use Checkout\Tokens\TokensClient;
use Checkout\Webhooks\Previous\WebhooksClient;

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

    public function __construct(CheckoutConfiguration $configuration)
    {
        $baseApiClient = $this->getBaseApiClient($configuration);
        parent::__construct($baseApiClient, $configuration);
        $this->sourcesClient = new SourcesClient($baseApiClient, $configuration);
        $this->tokensClient = new TokensClient($baseApiClient, $configuration);
        $this->instrumentsClient = new InstrumentsClient($baseApiClient, $configuration);
        $this->webhooksClient = new WebhooksClient($baseApiClient, $configuration);
        $this->eventsClient = new EventsClient($baseApiClient, $configuration);
        $this->paymentsClient = new PaymentsClient($baseApiClient, $configuration);
        $this->customersClient = new CustomersClient($baseApiClient, $configuration, AuthorizationType::$secretKey);
        $this->disputesClient = new DisputesClient($baseApiClient, $configuration, AuthorizationType::$secretKey);
        $this->paymentLinksClient = new PaymentLinksClient($baseApiClient, $configuration);
        $this->hostedPaymentsClient = new HostedPaymentsClient($baseApiClient, $configuration);
        $this->riskClient = new RiskClient($baseApiClient, $configuration);
        $this->reconciliationClient = new ReconciliationClient($baseApiClient, $configuration);
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

    /**
     * @param CheckoutConfiguration $configuration
     * @return ApiClient
     */
    private function getBaseApiClient(CheckoutConfiguration $configuration)
    {
        $baseUri = $configuration->getEnvironment()->getBaseUri();
        $subdomain = $configuration->getEnvironmentSubdomain();

        if ($subdomain !== null && $subdomain->getBaseUri() !== null) {
            $baseUri = $subdomain->getBaseUri();
        }
        return new ApiClient($configuration, $baseUri);
    }
}
