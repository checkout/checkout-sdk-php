<?php

namespace Checkout\Tests\Payments;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Common\AccountHolderIdentification;
use Checkout\Common\AccountHolderIdentificationType;
use Checkout\Common\Country;
use Checkout\Common\Currency;
use Checkout\Common\CustomerRequest;
use Checkout\Payments\DeviceDetails;
use Checkout\Payments\DeviceProvider;
use Checkout\Payments\Network;
use Checkout\Payments\ProcessingSettings;
use Checkout\Payments\Request\PaymentRequest;
use Checkout\Payments\Request\Source\RequestCardSource;
use Checkout\Payments\Request\Source\RequestTokenSource;
use Checkout\Payments\RiskRequest;
use Checkout\Payments\Sender\PaymentCorporateSender;
use Checkout\Payments\Sender\PaymentIndividualSender;
use Checkout\Payments\Sender\PaymentInstrumentSender;
use Checkout\Payments\ThreeDsRequest;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;
use Checkout\Tests\TestCardSource;
use Checkout\Tokens\CardTokenRequest;
use DateTime;

abstract class AbstractPaymentsIntegrationTest extends SandboxTestFixture
{
    protected static $payee_not_onboarded = "payee_not_onboarded";
    protected static $apm_service_unavailable = "apm_service_unavailable";

    /**
     * @before
     * @throws CheckoutAuthorizationException
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    public function before()
    {
        $this->init(PlatformType::$default);
    }

    /**
     * @param bool $shouldCapture
     * @param int $amount
     * @param DateTime|null $captureOn
     * @return mixed
     * @throws CheckoutApiException
     */
    protected function makeCardPayment($shouldCapture = false, $amount = 10, $captureOn = null)
    {
        $phone = $this->getPhone();
        $address = $this->getAddress();

        $requestCardSource = new RequestCardSource();
        $requestCardSource->name = TestCardSource::$VisaName;
        $requestCardSource->number = TestCardSource::$VisaNumber;
        $requestCardSource->expiry_year = TestCardSource::$VisaExpiryYear;
        $requestCardSource->expiry_month = TestCardSource::$VisaExpiryMonth;
        $requestCardSource->cvv = TestCardSource::$VisaCvv;
        $requestCardSource->billing_address = $address;
        $requestCardSource->phone = $phone;

        $customerRequest = new CustomerRequest();
        $customerRequest->email = $this->randomEmail();
        $customerRequest->name = "Customer";

        $identification = new AccountHolderIdentification();
        $identification->issuing_country = Country::$GT;
        $identification->number = "1234";
        $identification->type = AccountHolderIdentificationType::$driving_licence;

        $paymentIndividualSender = new PaymentIndividualSender();
        $paymentIndividualSender->first_name = "Mr";
        $paymentIndividualSender->last_name = "Test";
        $paymentIndividualSender->address = $address;
        $paymentIndividualSender->identification = $identification;

        $processingSettings = new ProcessingSettings();
        $processingSettings->order_id = "ORDER";

        $network = new Network();
        $network->ipv4 = "192.168.1.100";
        $network->tor = false;
        $network->vpn = false;
        $network->proxy = true;

        $provider = new DeviceProvider();
        $provider->name = "DeviceIDProviderExample";
        $provider->version = "1.0.0";

        $deviceDetails = new DeviceDetails();
        $deviceDetails->user_agent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64)...";
        $deviceDetails->network = $network;
        $deviceDetails->provider = $provider;
        $deviceDetails->timestamp = date('c');
        $deviceDetails->timezone = "+2";
        $deviceDetails->virtual_machine = false;
        $deviceDetails->incognito = false;
        $deviceDetails->jailbroken = false;
        $deviceDetails->rooted = false;
        $deviceDetails->java_enabled = false;
        $deviceDetails->javascript_enabled = true;
        $deviceDetails->language = "es-ES";
        $deviceDetails->color_depth = "24";
        $deviceDetails->screen_height = "1080";
        $deviceDetails->screen_width = "1920";

        $riskRequest = new RiskRequest();
        $riskRequest->enabled = true;
        $riskRequest->device_session_id = "dsid_jfgrt56dgrte64trgdfer34e56";
        $riskRequest->device = $deviceDetails;

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestCardSource;
        $paymentRequest->capture = $shouldCapture;
        $paymentRequest->reference = uniqid();
        $paymentRequest->amount = $amount;
        $paymentRequest->currency = Currency::$USD;
        $paymentRequest->customer = $customerRequest;
        $paymentRequest->sender = $paymentIndividualSender;
        $paymentRequest->processing_channel_id = getenv("CHECKOUT_PROCESSING_CHANNEL_ID");
        $paymentRequest->processing = $processingSettings;
        $paymentRequest->risk = $riskRequest;

        if (!is_null($captureOn)) {
            $paymentRequest->capture_on = $captureOn;
        }

        $paymentResponse = $this->checkoutApi->getPaymentsClient()->requestPayment($paymentRequest);

        $this->assertResponse($paymentResponse, "id");
        return $paymentResponse;
    }

    /**
     * @return mixed
     * @throws CheckoutApiException
     */
    protected function makeTokenPayment()
    {
        $cardTokenResponse = $this->requestToken();

        $requestTokenSource = new RequestTokenSource();
        $requestTokenSource->token = $cardTokenResponse["token"];

        $customerRequest = new CustomerRequest();
        $customerRequest->email = $this->randomEmail();

        $paymentInstrumentSender = new PaymentInstrumentSender();

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestTokenSource;
        $paymentRequest->capture = true;
        $paymentRequest->reference = uniqid();
        $paymentRequest->amount = 10;
        $paymentRequest->currency = Currency::$USD;
        $paymentRequest->customer = $customerRequest;
        $paymentRequest->sender = $paymentInstrumentSender;

        $paymentResponse = $this->checkoutApi->getPaymentsClient()->requestPayment($paymentRequest);
        $this->assertResponse($paymentResponse, "id");

        return $paymentResponse;
    }

    /**
     * @param bool $attemptN3d
     * @return mixed
     * @throws CheckoutApiException
     */
    protected function make3dsCardPayment($attemptN3d = false)
    {
        $requestCardSource = new RequestCardSource();
        $requestCardSource->name = TestCardSource::$VisaName;
        $requestCardSource->number = TestCardSource::$VisaNumber;
        $requestCardSource->expiry_year = TestCardSource::$VisaExpiryYear;
        $requestCardSource->expiry_month = TestCardSource::$VisaExpiryMonth;
        $requestCardSource->cvv = TestCardSource::$VisaCvv;
        $requestCardSource->billing_address = $this->getAddress();
        $requestCardSource->phone = $this->getPhone();

        $threeDsRequest = new ThreeDsRequest();
        $threeDsRequest->enabled = true;
        $threeDsRequest->attempt_n3d = $attemptN3d;
        $threeDsRequest->eci = $attemptN3d ? "05" : "";
        $threeDsRequest->cryptogram = $attemptN3d ? "AgAAAAAAAIR8CQrXcIhbQAAAAAA" : "";
        $threeDsRequest->xid = $attemptN3d ? "MDAwMDAwMDAwMDAwMDAwMzIyNzY" : "";
        $threeDsRequest->version = "2.0.1";

        $customerRequest = new CustomerRequest();
        $customerRequest->email = $this->randomEmail();
        $customerRequest->name = "Customer";

        $paymentCorporateSender = new PaymentCorporateSender();
        $paymentCorporateSender->company_name = "Testing Inc.";
        $paymentCorporateSender->address = $this->getAddress();

        $paymentRequest = new PaymentRequest();
        $paymentRequest->source = $requestCardSource;
        $paymentRequest->capture = false;
        $paymentRequest->reference = uniqid("make3dsCardPayment");
        $paymentRequest->amount = 10;
        $paymentRequest->currency = Currency::$USD;
        $paymentRequest->customer = $customerRequest;
        $paymentRequest->three_ds = $threeDsRequest;
        $paymentRequest->sender = $paymentCorporateSender;
        $paymentRequest->success_url = "https://test.checkout.com/success";
        $paymentRequest->failure_url = "https://test.checkout.com/failure";

        $paymentResponse = $this->checkoutApi->getPaymentsClient()->requestPayment($paymentRequest);
        $this->assertResponse($paymentResponse, "id");

        return $paymentResponse;
    }

    /**
     * @return mixed
     * @throws CheckoutApiException
     */
    protected function requestToken()
    {
        $cardTokenRequest = new CardTokenRequest();
        $cardTokenRequest->name = TestCardSource::$VisaName;
        $cardTokenRequest->number = TestCardSource::$VisaNumber;
        $cardTokenRequest->expiry_year = TestCardSource::$VisaExpiryYear;
        $cardTokenRequest->expiry_month = TestCardSource::$VisaExpiryMonth;
        $cardTokenRequest->cvv = TestCardSource::$VisaCvv;
        $cardTokenRequest->billing_address = $this->getAddress();
        $cardTokenRequest->phone = $this->getPhone();

        $cardTokenResponse = $this->checkoutApi->getTokensClient()->requestCardToken($cardTokenRequest);
        $this->assertResponse($cardTokenResponse, "token");

        return $cardTokenResponse;
    }

}
