<?php

namespace Checkout\Tests;

use Checkout\OAuthScope;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class OAuthScopeTest extends TestCase
{

    /**
     * The static property is the only place a scope's wire value is written down, and
     * OAuthSdkCredentials builds the token request by imploding those values
     * (OAuthSdkCredentials.php:101) rather than reading the property names. A typo is therefore
     * invisible until the token endpoint rejects it, and it rejects the whole request when one
     * requested scope is undefined -- so a caller would lose every scope it asked for alongside
     * the bad one.
     *
     * Values come from components.securitySchemes.OAuth.flows.clientCredentials.scopes in
     * shared/swagger-latest.json.
     *
     * @test
     */
    public static function shouldExposeDocumentedBalancesScopeValues()
    {
        self::assertEquals("balances", OAuthScope::$Balances);
        self::assertEquals("balances:view", OAuthScope::$BalancesView);
        self::assertEquals("balances:top-up-instructions", OAuthScope::$BalancesTopUpInstructions);
    }

    /**
     * The scopes added when this class was synced against the spec.
     *
     * vault:tokens-metadata is not declared in clientCredentials.scopes at all: it appears only in
     * the security requirement of GET /tokens/{tokenId}/metadata. compliance-requests is in the
     * same position, alongside the :read and :respond variants this SDK already shipped.
     *
     * @test
     */
    public static function shouldExposeDocumentedValuesForScopesAddedInSpecSync()
    {
        self::assertEquals("compliance-requests", OAuthScope::$ComplianceRequests);
        self::assertEquals("flow:reflow", OAuthScope::$FlowReflow);
        self::assertEquals("issuing-disputes", OAuthScope::$IssuingDisputes);
        self::assertEquals("vault:tokens-metadata", OAuthScope::$VaultTokensMetadata);
    }

    /**
     * These five scopes appear nowhere in the specification -- neither in the clientCredentials
     * scope map nor in any operation's security requirement -- so a sweep driven by the spec alone
     * would delete them. They are kept deliberately: the authorization server still grants them and
     * callers still request them. marketplace is the proof: the sandbox payouts client is
     * provisioned for it and answers a request for accounts with invalid_scope.
     *
     * @test
     */
    public static function shouldRetainTheLegacyScopesTheSpecificationOmits()
    {
        self::assertEquals("issuing:card-mgmt", OAuthScope::$IssuingCardMgmt);
        self::assertEquals("issuing:client", OAuthScope::$IssuingClient);
        self::assertEquals("marketplace", OAuthScope::$Marketplace);
        self::assertEquals("middleware:gateway", OAuthScope::$MiddlewareGateway);
        self::assertEquals("middleware:payment-context", OAuthScope::$MiddlewarePaymentContext);
    }

    /**
     * $PaymentContext and $GatewayPaymentContexts read alike but are unrelated scopes, so this pins
     * which is which: the spec requires the former for GET /payment-contexts/{id} and the latter
     * for POST /payment-contexts.
     *
     * "Payment Context" is the only scope whose value contains a space and a capital letter, which
     * is almost certainly a spec authoring defect -- asserted verbatim because that is the value
     * the authorization server is documented to accept.
     *
     * @test
     */
    public static function shouldDistinguishTheTwoPaymentContextScopes()
    {
        self::assertEquals("Payment Context", OAuthScope::$PaymentContext);
        self::assertEquals("gateway:payment-contexts", OAuthScope::$GatewayPaymentContexts);
    }

    /**
     * A blank value cannot be caught by the per-scope assertions above, which only read the
     * properties they name. It would be imploded into the scope parameter as an empty entry, which
     * the token endpoint rejects for the whole request -- costing the caller every other scope it
     * asked for.
     *
     * @test
     */
    public static function shouldExposeANonBlankWireValueForEveryProperty()
    {
        foreach (self::scopes() as $name => $value) {
            self::assertTrue(
                is_string($value) && trim($value) !== "",
                "\$" . $name . " has a blank wire value"
            );
        }
    }

    /**
     * Two properties sharing a wire value means one of them is a copy-paste error, and it cannot be
     * caught by the per-scope assertions above, which only ever read the property they name. The
     * consequence is silent in both directions: a caller selecting the mistyped property requests a
     * scope it did not ask for, and the scope that property was supposed to carry is left with no
     * property at all, so it becomes unreachable through this class.
     *
     * @test
     */
    public static function shouldNotReuseAWireValueAcrossProperties()
    {
        $values = array_values(self::scopes());
        $duplicates = array_keys(array_filter(array_count_values($values), function ($count) {
            return $count > 1;
        }));

        self::assertEmpty($duplicates, "wire values used by more than one property: "
            . implode(", ", $duplicates));
    }

    /**
     * Properties are maintained in alphabetical order so that the next spec sync produces a
     * readable diff instead of scattering additions through the file, and so the ordering matches
     * the other Checkout SDKs.
     *
     * @test
     */
    public static function shouldDeclarePropertiesInAlphabeticalOrder()
    {
        $declared = array_map("strtolower", array_keys(self::scopes()));
        $sorted = $declared;
        sort($sorted, SORT_STRING);

        self::assertEquals($sorted, $declared);
    }

    /**
     * Declaration order is significant to shouldDeclarePropertiesInAlphabeticalOrder, and
     * ReflectionClass::getStaticProperties preserves it.
     *
     * @return array property name => wire value
     */
    private static function scopes()
    {
        return (new ReflectionClass(OAuthScope::class))->getStaticProperties();
    }

}
