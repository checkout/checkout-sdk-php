<?php

namespace Checkout\Tests\Balances;

use PHPUnit\Framework\TestCase;

/**
 * Response-shape tests for GET /entities/{entityId}/currency-accounts/{currencyAccountId}/top-up-instructions.
 *
 * This SDK has no response classes for balances: the client returns decoded JSON, so what needs
 * pinning is the shape the PHPDoc on BalancesClient::retrieveTopUpInstructions promises callers.
 * BalancesIntegrationTest tolerates 403/404, which means it asserts nothing whenever the sandbox
 * denies the call -- these tests cover the shape unconditionally.
 *
 * TopUpBankDetails declares no `required` array in the spec, so domestic-only, international-only
 * and an empty bank_details are all legal 200 bodies. A caller that assumes both rails are always
 * present is the defect these tests guard against.
 *
 * Every value below is a field-level `example` from shared/swagger-latest.json.
 */
class TopUpInstructionsSerializationTest extends TestCase
{
    private const FULL_RAIL_JSON = '{
        "beneficiary_account_name": "Acme Inc",
        "beneficiary_address": "1 Example Street, Exampleville, EX, 00000, US",
        "bank_name": "Example Bank",
        "bank_address": "1 Example Street, Exampleville, EX, 00000, US",
        "account_number": "1234567890",
        "sort_code": "000000",
        "routing_number": "000000000",
        "iban": "GB00EXAM00000000000000",
        "swift_code": "TESTUS00XXX"
    }';

    private function assertFullRail(array $rail)
    {
        $this->assertSame("Acme Inc", $rail["beneficiary_account_name"]);
        $this->assertSame("1 Example Street, Exampleville, EX, 00000, US", $rail["beneficiary_address"]);
        $this->assertSame("Example Bank", $rail["bank_name"]);
        $this->assertSame("1 Example Street, Exampleville, EX, 00000, US", $rail["bank_address"]);
        $this->assertSame("1234567890", $rail["account_number"]);
        $this->assertSame("000000", $rail["sort_code"]);
        $this->assertSame("000000000", $rail["routing_number"]);
        $this->assertSame("GB00EXAM00000000000000", $rail["iban"]);
        $this->assertSame("TESTUS00XXX", $rail["swift_code"]);
    }

    private function decodeWithBankDetails(string $bankDetailsJson): array
    {
        return json_decode('{
            "currency_account_id": "ca_g5y7d6jo4e2urgforcbf2ey5jm",
            "currency": "USD",
            "payment_reference": "TP-ABC123",
            "bank_details": ' . $bankDetailsJson . '
        }', true);
    }

    /**
     * P1 -- both rails, all 9 fields each.
     *
     * @test
     */
    public function shouldDecodeBothRailsWithEveryField()
    {
        $response = $this->decodeWithBankDetails('{
            "domestic": ' . self::FULL_RAIL_JSON . ',
            "international": ' . self::FULL_RAIL_JSON . '
        }');

        // All four top-level properties are `required` in the spec.
        $this->assertSame("ca_g5y7d6jo4e2urgforcbf2ey5jm", $response["currency_account_id"]);
        $this->assertSame("USD", $response["currency"]);
        $this->assertSame("TP-ABC123", $response["payment_reference"]);
        $this->assertArrayHasKey("bank_details", $response);

        $this->assertArrayHasKey("domestic", $response["bank_details"]);
        $this->assertArrayHasKey("international", $response["bank_details"]);
        $this->assertFullRail($response["bank_details"]["domestic"]);
        $this->assertFullRail($response["bank_details"]["international"]);
    }

    /**
     * P2 -- domestic only. The US-shaped rail: routing_number is returned for United States
     * domestic transfers, and the international-only fields are absent.
     *
     * @test
     */
    public function shouldDecodeDomesticOnlyWithInternationalAbsent()
    {
        $response = $this->decodeWithBankDetails('{
            "domestic": {
                "beneficiary_account_name": "Acme Inc",
                "beneficiary_address": "1 Example Street, Exampleville, EX, 00000, US",
                "bank_name": "Example Bank",
                "bank_address": "1 Example Street, Exampleville, EX, 00000, US",
                "account_number": "1234567890",
                "routing_number": "000000000"
            }
        }');

        $this->assertArrayHasKey("domestic", $response["bank_details"]);
        $this->assertArrayNotHasKey("international", $response["bank_details"]);

        $domestic = $response["bank_details"]["domestic"];
        $this->assertSame("Acme Inc", $domestic["beneficiary_account_name"]);
        $this->assertSame("Example Bank", $domestic["bank_name"]);
        $this->assertSame("000000000", $domestic["routing_number"]);
        $this->assertArrayNotHasKey("iban", $domestic);
        $this->assertArrayNotHasKey("swift_code", $domestic);
    }

    /**
     * P3 -- international only. iban + swift_code, per "Returned for international transfers".
     *
     * @test
     */
    public function shouldDecodeInternationalOnlyWithDomesticAbsent()
    {
        $response = $this->decodeWithBankDetails('{
            "international": {
                "beneficiary_account_name": "Acme Inc",
                "bank_name": "Example Bank",
                "iban": "GB00EXAM00000000000000",
                "swift_code": "TESTUS00XXX"
            }
        }');

        $this->assertArrayHasKey("international", $response["bank_details"]);
        $this->assertArrayNotHasKey("domestic", $response["bank_details"]);

        $international = $response["bank_details"]["international"];
        $this->assertSame("GB00EXAM00000000000000", $international["iban"]);
        $this->assertSame("TESTUS00XXX", $international["swift_code"]);
        $this->assertArrayNotHasKey("sort_code", $international);
        $this->assertArrayNotHasKey("routing_number", $international);
    }

    /**
     * An empty bank_details is a legal body: the spec puts no `required` array on
     * TopUpBankDetails, so neither rail is guaranteed.
     *
     * @test
     */
    public function shouldDecodeEmptyBankDetailsWithNeitherRail()
    {
        $response = $this->decodeWithBankDetails('{}');

        $this->assertSame([], $response["bank_details"]);
        $this->assertArrayNotHasKey("domestic", $response["bank_details"]);
        $this->assertArrayNotHasKey("international", $response["bank_details"]);
        // The four required top-level fields are still there.
        $this->assertSame("TP-ABC123", $response["payment_reference"]);
    }

    /**
     * Roundtrip -- re-encoding the decoded body must reproduce the wire names exactly. This is
     * what catches an accidental camelCase or snake_case rewrite in the documented shape.
     *
     * @test
     */
    public function shouldRoundTripPreservingWireNames()
    {
        $original = $this->decodeWithBankDetails('{
            "domestic": ' . self::FULL_RAIL_JSON . ',
            "international": ' . self::FULL_RAIL_JSON . '
        }');

        $roundTripped = json_decode(json_encode($original), true);

        $this->assertSame($original, $roundTripped);
        foreach (["currency_account_id", "currency", "payment_reference", "bank_details"] as $key) {
            $this->assertArrayHasKey($key, $roundTripped);
        }
        foreach (array_keys(json_decode(self::FULL_RAIL_JSON, true)) as $key) {
            $this->assertArrayHasKey($key, $roundTripped["bank_details"]["domestic"]);
        }
    }
}
