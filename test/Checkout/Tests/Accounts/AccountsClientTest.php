<?php

namespace Checkout\Tests\Accounts;

use Checkout\Accounts\AccountsClient;
use Checkout\Accounts\AccountsFileRequest;
use Checkout\Accounts\AccountsPaymentInstrument;
use Checkout\Accounts\OnboardEntityRequest;
use Checkout\Accounts\PaymentInstrumentRequest;
use Checkout\Accounts\PaymentInstrumentsQuery;
use Checkout\Accounts\UpdatePaymentInstrumentRequest;
use Checkout\Accounts\UpdateScheduleRequest;
use Checkout\Accounts\ReserveRules\Requests\CreateReserveRuleRequest;
use Checkout\Accounts\ReserveRules\Requests\UpdateReserveRuleRequest;
use Checkout\Accounts\ReserveRules\Entities\Rolling;
use Checkout\Accounts\ReserveRules\Entities\HoldingDuration;
use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Common\Currency;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class AccountsClientTest extends UnitTestFixture
{
    /**
     * @var AccountsClient
     */
    private $client;

    /**
     * @before
     * @throws CheckoutAuthorizationException
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    public function init()
    {
        $this->initMocks(PlatformType::$default_oauth);
        $this->client = new AccountsClient(
            $this->apiClient,
            $this->apiClient,
            $this->configuration
        );
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateEntity()
    {
        $this->apiClient
            ->method("post")
            ->willReturn(["response"]);

        $response = $this->client->CreateEntity(new OnboardEntityRequest());

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetEntity()
    {
        $this->apiClient
            ->method("get")
            ->willReturn(["response"]);

        $response = $this->client->GetEntity("entity_id");

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUpdateEntity()
    {
        $this->apiClient
            ->method("put")
            ->willReturn(["response"]);

        $response = $this->client->UpdateEntity("entity_id", new OnboardEntityRequest());

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreatePaymentInstrument()
    {
        $this->apiClient
            ->method("post")
            ->willReturn(["response"]);

        $response = $this->client->CreatePaymentInstrument("entity_id", new AccountsPaymentInstrument());

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSubmitFile()
    {
        $this->apiClient
            ->method("submitFileFilesApi")
            ->willReturn(["response"]);

        $fileRequest = new AccountsFileRequest();
        $fileRequest->file = "filepath";
        $fileRequest->purpose = "individual";
        $fileRequest->content_type = "image/jpeg";

        $response = $this->client->submitFile($fileRequest);

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUpdatePayoutSchedule()
    {
        $this->apiClient
            ->method("put")
            ->willReturn(["response"]);

        $response = $this->client->updatePayoutSchedule("entity_id", Currency::$USD, new UpdateScheduleRequest());

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRetrievePayoutSchedule()
    {
        $this->apiClient
            ->method("get")
            ->willReturn(["response"]);

        $response = $this->client->retrievePayoutSchedule("entity_id");

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRetrievePaymentInstrumentDetails()
    {
        $this->apiClient
            ->method("get")
            ->willReturn(["response"]);

        $response = $this->client->retrievePaymentInstrumentDetails("entity_id", "instrument_id");

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldQueryPaymentInstruments()
    {
        $this->apiClient
            ->method("query")
            ->willReturn(["response"]);

        $response = $this->client->queryPaymentInstruments("entity_id", new PaymentInstrumentsQuery());

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateBankPaymentInstrument()
    {
        $this->apiClient
            ->method("post")
            ->willReturn(["response"]);

        $response = $this->client->createBankPaymentInstrument("entity_id", new PaymentInstrumentRequest());

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUpdateBankPaymentInstrument()
    {
        $this->apiClient
            ->method("patch")
            ->willReturn(["response"]);

        $response = $this->client->updateBankPaymentInstrumentDetails(
            "entity_id",
            "instrument_id",
            new UpdatePaymentInstrumentRequest()
        );

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetSubEntityMembers()
    {
        $this->apiClient
            ->method("get")
            ->willReturn(["data" => [["user_id" => "usr_test123"]]]);

        $response = $this->client->getSubEntityMembers("entity_id");

        $this->assertNotNull($response);
        $this->assertArrayHasKey("data", $response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldReinviteSubEntityMember()
    {
        $this->apiClient
            ->method("put")
            ->willReturn(["id" => "usr_test123"]);

        $response = $this->client->reinviteSubEntityMember("entity_id", "user_id");

        $this->assertNotNull($response);
        $this->assertArrayHasKey("id", $response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateReserveRule()
    {
        $this->apiClient
            ->method("post")
            ->willReturn(["id" => "rsv_test123", "_links" => ["self" => ["href" => "/reserve-rules/rsv_test123"]]]);

        $request = $this->buildCreateReserveRuleRequest();
        $response = $this->client->createReserveRule("entity_id", $request);

        $this->assertNotNull($response);
        $this->assertArrayHasKey("id", $response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetReserveRules()
    {
        $this->apiClient
            ->method("get")
            ->willReturn(["data" => [["id" => "rsv_test123", "type" => "rolling"]]]);

        $response = $this->client->getReserveRules("entity_id");

        $this->assertNotNull($response);
        $this->assertArrayHasKey("data", $response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetReserveRuleDetails()
    {
        $this->apiClient
            ->method("get")
            ->willReturn(["id" => "rsv_test123", "type" => "rolling", "valid_from" => "2024-01-01T00:00:00Z"]);

        $response = $this->client->getReserveRuleDetails("entity_id", "reserve_rule_id");

        $this->assertNotNull($response);
        $this->assertArrayHasKey("id", $response);
        $this->assertArrayHasKey("type", $response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUpdateReserveRule()
    {
        $this->apiClient
            ->method("put")
            ->willReturn(["id" => "rsv_test123", "_links" => ["self" => ["href" => "/reserve-rules/rsv_test123"]]]);

        $request = $this->buildUpdateReserveRuleRequest();
        $response = $this->client->updateReserveRule("entity_id", "reserve_rule_id", "etag_value", $request);

        $this->assertNotNull($response);
        $this->assertArrayHasKey("id", $response);
    }

    /**
     * Helper method to build CreateReserveRuleRequest
     */
    private function buildCreateReserveRuleRequest()
    {
        $request = new CreateReserveRuleRequest();
        $request->type = "rolling";
        $request->valid_from = "2024-01-01T00:00:00Z";
        
        $rolling = new Rolling();
        $rolling->percentage = 10.5;
        
        $holdingDuration = new HoldingDuration();
        $holdingDuration->weeks = 4;
        $rolling->holding_duration = $holdingDuration;
        
        $request->rolling = $rolling;
        
        return $request;
    }

    /**
     * Helper method to build UpdateReserveRuleRequest
     */
    private function buildUpdateReserveRuleRequest()
    {
        $request = new UpdateReserveRuleRequest();
        $request->type = "rolling";
        
        $rolling = new Rolling();
        $rolling->percentage = 15.0;
        
        $holdingDuration = new HoldingDuration();
        $holdingDuration->weeks = 6;
        $rolling->holding_duration = $holdingDuration;
        
        $request->rolling = $rolling;
        
        return $request;
    }
}
