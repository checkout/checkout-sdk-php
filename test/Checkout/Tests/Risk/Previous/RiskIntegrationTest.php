<?php

namespace Checkout\Tests\Risk\Previous;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Common\Address;
use Checkout\Common\Country;
use Checkout\Common\Currency;
use Checkout\Common\CustomerRequest;
use Checkout\Common\InstrumentType;
use Checkout\Instruments\Previous\CreateInstrumentRequest;
use Checkout\Instruments\Previous\InstrumentAccountHolder;
use Checkout\PlatformType;
use Checkout\Risk\Device;
use Checkout\Risk\Location;
use Checkout\Risk\PreAuthentication\PreAuthenticationAssessmentRequest;
use Checkout\Risk\PreCapture\AuthenticationResult;
use Checkout\Risk\PreCapture\AuthorizationResult;
use Checkout\Risk\PreCapture\PreCaptureAssessmentRequest;
use Checkout\Risk\RiskPayment;
use Checkout\Risk\RiskShippingDetails;
use Checkout\Risk\Source\CardSourcePrism;
use Checkout\Risk\Source\CustomerSourcePrism;
use Checkout\Risk\Source\IdSourcePrism;
use Checkout\Risk\Source\RiskPaymentRequestSource;
use Checkout\Risk\Source\RiskRequestTokenSource;
use Checkout\Tests\SandboxTestFixture;
use Checkout\Tests\TestCardSource;
use Checkout\Tokens\CardTokenRequest;
use DateTime;

class RiskIntegrationTest extends SandboxTestFixture
{

    /**
     * @before
     * @throws CheckoutAuthorizationException
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    public function before()
    {
        $this->init(PlatformType::$previous);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldPreCaptureAndAuthenticateCard()
    {
        $address = new Address();
        $address->address_line1 = "123 Street";
        $address->address_line2 = "Hollywood Avenue";
        $address->city = "Los Angeles";
        $address->state = "CA";
        $address->zip = "91001";
        $address->country = Country::$US;

        $cardSourcePrism = new CardSourcePrism();
        $cardSourcePrism->billing_address = $address;
        $cardSourcePrism->expiry_month = TestCardSource::$VisaExpiryMonth;
        $cardSourcePrism->expiry_year = TestCardSource::$VisaExpiryYear;
        $cardSourcePrism->number = TestCardSource::$VisaNumber;

        $this->doAuthenticationAssessmentRequest($cardSourcePrism);
        $this->doPreCaptureAssessmentRequest($cardSourcePrism);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldPreCaptureAndAuthenticateCustomer()
    {
        $customerRequest = new \Checkout\Customers\CustomerRequest();
        $customerRequest->email = $this->randomEmail();
        $customerRequest->name = "User";
        $customerRequest->phone = $this->getPhone();

        $customerResponse = $this->previousApi->getCustomersClient()->create($customerRequest);

        $customerSourcePrism = new CustomerSourcePrism();
        $customerSourcePrism->id = $customerResponse["id"];

        $this->doAuthenticationAssessmentRequest($customerSourcePrism);
        $this->doPreCaptureAssessmentRequest($customerSourcePrism);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldPreCaptureAndAuthenticateId()
    {
        $cardTokenRequest = new CardTokenRequest();
        $cardTokenRequest->name = TestCardSource::$VisaName;
        $cardTokenRequest->number = TestCardSource::$VisaNumber;
        $cardTokenRequest->expiry_year = TestCardSource::$VisaExpiryYear;
        $cardTokenRequest->expiry_month = TestCardSource::$VisaExpiryMonth;
        $cardTokenRequest->cvv = TestCardSource::$VisaCvv;
        $cardTokenRequest->billing_address = $this->getAddress();
        $cardTokenRequest->phone = $this->getPhone();

        $cardToken = $this->previousApi->getTokensClient()->requestCardToken($cardTokenRequest);

        $instrumentAccountHolder = new InstrumentAccountHolder();
        $instrumentAccountHolder->billing_address = $this->getAddress();
        $instrumentAccountHolder->phone = $this->getPhone();

        $createInstrumentRequest = new CreateInstrumentRequest();
        $createInstrumentRequest->type = InstrumentType::$token;
        $createInstrumentRequest->token = $cardToken["token"];
        $createInstrumentRequest->account_holder = $instrumentAccountHolder;

        $instrumentsResponse = $this->previousApi->getInstrumentsClient()->create($createInstrumentRequest);

        $idSourcePrism = new IdSourcePrism();
        $idSourcePrism->id = $instrumentsResponse["id"];
        $idSourcePrism->cvv = TestCardSource::$VisaCvv;

        $this->doAuthenticationAssessmentRequest($idSourcePrism);
        $this->doPreCaptureAssessmentRequest($idSourcePrism);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldPreCaptureAndAuthenticateToken()
    {
        $cardTokenRequest = new CardTokenRequest();
        $cardTokenRequest->name = TestCardSource::$VisaName;
        $cardTokenRequest->number = TestCardSource::$VisaNumber;
        $cardTokenRequest->expiry_year = TestCardSource::$VisaExpiryYear;
        $cardTokenRequest->expiry_month = TestCardSource::$VisaExpiryMonth;
        $cardTokenRequest->cvv = TestCardSource::$VisaCvv;
        $cardTokenRequest->billing_address = $this->getAddress();
        $cardTokenRequest->phone = $this->getPhone();

        $cardToken = $this->previousApi->getTokensClient()->requestCardToken($cardTokenRequest);

        $riskRequestTokenSource = new RiskRequestTokenSource();
        $riskRequestTokenSource->token = $cardToken["token"];
        $riskRequestTokenSource->phone = $this->getPhone();
        $riskRequestTokenSource->billing_address = $this->getAddress();

        $this->doAuthenticationAssessmentRequest($riskRequestTokenSource);
        $this->doPreCaptureAssessmentRequest($riskRequestTokenSource);
    }

    /**
     * @param RiskPaymentRequestSource $requestSource
     * @throws CheckoutApiException
     */
    private function doAuthenticationAssessmentRequest(RiskPaymentRequestSource $requestSource)
    {
        $customerRequest = new CustomerRequest();
        $customerRequest->email = $this->randomEmail();
        $customerRequest->name = "name";

        $preAuthenticationAssessmentRequest = new PreAuthenticationAssessmentRequest();
        $preAuthenticationAssessmentRequest->date = new DateTime();
        $preAuthenticationAssessmentRequest->source = $requestSource;
        $preAuthenticationAssessmentRequest->customer = $customerRequest;
        $preAuthenticationAssessmentRequest->payment = $this->getRiskPayment();
        $preAuthenticationAssessmentRequest->shipping = $this->getRiskShippingDetails();
        $preAuthenticationAssessmentRequest->reference = "ORD-1011-87AH";
        $preAuthenticationAssessmentRequest->description = "Set of 3 masks";
        $preAuthenticationAssessmentRequest->amount = 6540;
        $preAuthenticationAssessmentRequest->currency = Currency::$GBP;
        $preAuthenticationAssessmentRequest->device = $this->getDevice();
        $preAuthenticationAssessmentRequest->metadata = array("VoucherCode" => "loyalty_10", "discountApplied" => "10", "customer_id" => "2190EF321");

        $response = $this->previousApi->getRiskClient()->requestPreAuthenticationRiskScan($preAuthenticationAssessmentRequest);
        $this->assertResponse(
            $response,
            "assessment_id",
            "result",
            "result.decision",
            //"result.details",
            "_links"
        );
    }

    /**
     * @param RiskPaymentRequestSource $requestSource
     * @throws CheckoutApiException
     */
    private function doPreCaptureAssessmentRequest(RiskPaymentRequestSource $requestSource)
    {
        $customerRequest = new CustomerRequest();
        $customerRequest->email = $this->randomEmail();
        $customerRequest->name = "name";

        $authenticationResult = new AuthenticationResult();
        $authenticationResult->attempted = true;
        $authenticationResult->challenged = true;
        $authenticationResult->liability_shifted = true;
        $authenticationResult->method = "3ds";
        $authenticationResult->succeeded = true;
        $authenticationResult->version = "2.0";

        $authorizationResult = new AuthorizationResult();
        $authorizationResult->avs_code = "V";
        $authorizationResult->cvv_result = "N";

        $preCaptureAssessmentRequest = new PreCaptureAssessmentRequest();
        $preCaptureAssessmentRequest->date = new DateTime();
        $preCaptureAssessmentRequest->source = $requestSource;
        $preCaptureAssessmentRequest->customer = $customerRequest;
        $preCaptureAssessmentRequest->payment = $this->getRiskPayment();
        $preCaptureAssessmentRequest->shipping = $this->getRiskShippingDetails();
        $preCaptureAssessmentRequest->amount = 6540;
        $preCaptureAssessmentRequest->currency = Currency::$GBP;
        $preCaptureAssessmentRequest->device = $this->getDevice();
        $preCaptureAssessmentRequest->metadata = array("VoucherCode" => "loyalty_10", "discountApplied" => "10", "customer_id" => "2190EF321");
        $preCaptureAssessmentRequest->authentication_result = $authenticationResult;
        $preCaptureAssessmentRequest->authorization_result = $authorizationResult;

        $response = $this->previousApi->getRiskClient()->requestPreCaptureRiskScan($preCaptureAssessmentRequest);
        $this->assertResponse(
            $response,
            "assessment_id",
            "result",
            "result.decision",
            //"result.details",
            "_links"
        );
    }

    /**
     * @return RiskShippingDetails
     */
    private function getRiskShippingDetails()
    {
        $riskShippingDetails = new RiskShippingDetails();
        $riskShippingDetails->address = $this->getAddress();

        return $riskShippingDetails;
    }

    /**
     * @return Device
     */
    private function getDevice()
    {
        $location = new Location();
        $location->latitude = "51.5107";
        $location->longitude = "0.1313";

        $device = new Device();
        $device->location = $location;
        $device->type = "Phone";
        $device->os = "ISO";
        $device->model = "iPhone X";
        $device->date = new DateTime();
        $device->user_agent = "Mozilla/5.0 (iPhone; CPU iPhone OS 11_0 like Mac OS X) AppleWebKit/604.1.38 (KHTML, like Gecko) Version/11.0 Mobile/15A372 Safari/604.1";
        $device->fingerprint = "34304a9e3fg09302";
        return $device;
    }

    /**
     * @return RiskPayment
     */
    private function getRiskPayment()
    {
        $riskPayment = new RiskPayment();
        $riskPayment->psp = "CheckoutSdk.com";
        $riskPayment->id = "78453878";
        return $riskPayment;
    }

}
