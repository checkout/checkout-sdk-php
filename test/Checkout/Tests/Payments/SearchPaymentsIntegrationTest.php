<?php

namespace Checkout\Tests\Payments;

use Checkout\CheckoutApiException;
use Checkout\Payments\SearchPaymentRequest;
use Checkout\PlatformType;
use DateTime;

class SearchPaymentsIntegrationTest extends AbstractPaymentsIntegrationTest
{
    /**
     * @before
     * @throws \Checkout\CheckoutAuthorizationException
     * @throws \Checkout\CheckoutArgumentException
     * @throws \Checkout\CheckoutException
     */
    public function before(): void
    {
        $this->markTestSkipped(
            "Avoid creating payments all the time, and will slow the pipeline, search is a very slow operation"
        );
        $this->init(PlatformType::$default_oauth);
    }
    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSearchPayments()
    {
        $paymentResponse = $this->makeCardPayment(true);

        $searchRequest = new SearchPaymentRequest();
        $searchRequest->query = "id:'" . $paymentResponse["id"] . "'";
        $searchRequest->limit = 10;
        $searchRequest->from = (new DateTime())->modify('-5 minutes')->format('c');
        $searchRequest->to = (new DateTime())->modify('+5 minutes')->format('c');

        $response = $this->retriable(
            function () use ($searchRequest) {
                return $this->checkoutApi->getPaymentsClient()->searchPayments($searchRequest);
            },
            $this->searchHasResults()
        );

        $this->assertResponse($response, "data");
        $this->assertNotEmpty($response["data"], "Search should return at least one result");
        
        if (count($response["data"]) > 0) {
            $firstResult = $response["data"][0];
            $this->assertEquals($paymentResponse["id"], $firstResult["id"]);
            $this->assertResponse($firstResult, "id", "status", "amount", "currency");
        }
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSearchPaymentsByReference()
    {
        $paymentResponse = $this->makeCardPayment(true);

        $searchRequest = new SearchPaymentRequest();
        $searchRequest->query = "reference:'" . $paymentResponse["reference"] . "'";
        $searchRequest->limit = 5;

        $response = $this->retriable(
            function () use ($searchRequest) {
                return $this->checkoutApi->getPaymentsClient()->searchPayments($searchRequest);
            },
            $this->searchHasResults()
        );

        $this->assertResponse($response, "data");
        $this->assertNotEmpty($response["data"], "Search by reference should return results");
        
        if (count($response["data"]) > 0) {
            $firstResult = $response["data"][0];
            $this->assertEquals($paymentResponse["reference"], $firstResult["reference"]);
        }
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSearchPaymentsByAmount()
    {
        $paymentResponse = $this->makeCardPayment(true);
        $amount = $paymentResponse["amount"];

        $searchRequest = new SearchPaymentRequest();
        $searchRequest->query = "amount:" . $amount;
        $searchRequest->limit = 10;

        $response = $this->checkoutApi->getPaymentsClient()->searchPayments($searchRequest);

        $this->assertResponse($response, "data");
        $this->assertTrue(is_array($response["data"]));
        
        // Verify all returned payments have the searched amount
        foreach ($response["data"] as $payment) {
            $this->assertEquals($amount, $payment["amount"]);
        }
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldHandleSearchValidationErrors()
    {
        $searchRequest = new SearchPaymentRequest();
        $searchRequest->query = "invalid_field:'test_value'"; // Invalid query field
        $searchRequest->limit = 10;

        try {
            $this->checkoutApi->getPaymentsClient()->searchPayments($searchRequest);
            $this->fail("Expected CheckoutApiException for invalid search query");
        } catch (CheckoutApiException $ex) {
            $statusCode = $ex->http_metadata->getStatusCode();
            $this->assertEquals(422, $statusCode, "Expected 422 for invalid query");
            
            $this->assertArrayHasKey("error_codes", $ex->error_details);
            $this->assertContains("unknown_attribute", $ex->error_details["error_codes"]);
        }
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSearchPaymentsWithDateRange()
    {
        $paymentResponse = $this->makeCardPayment(true);

        $searchRequest = new SearchPaymentRequest();
        $searchRequest->query = "currency:'USD'"; // Search for USD payments
        $searchRequest->limit = 10;
        $searchRequest->from = (new DateTime())->modify('-1 hour')->format('c');
        $searchRequest->to = (new DateTime())->modify('+1 hour')->format('c');

        $response = $this->checkoutApi->getPaymentsClient()->searchPayments($searchRequest);

        $this->assertResponse($response, "data");
        $this->assertTrue(is_array($response["data"]));
        
        // Should find at least our test payment
        $foundPayment = false;
        foreach ($response["data"] as $payment) {
            if ($payment["id"] === $paymentResponse["id"]) {
                $foundPayment = true;
                break;
            }
        }
        $this->assertTrue($foundPayment, "Should find the test payment in date range");
    }

    /**
     * @return \Closure
     */
    private function searchHasResults()
    {
        return function ($response) {
            return array_key_exists("data", $response) &&
                   count($response["data"]) > 0;
        };
    }
}
