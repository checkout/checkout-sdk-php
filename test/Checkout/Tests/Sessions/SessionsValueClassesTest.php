<?php

namespace Checkout\Tests\Sessions;

use Checkout\Sessions\AuthenticationType;
use Checkout\Sessions\Category;
use Checkout\Sessions\SessionScheme;
use Checkout\Sessions\TransactionType;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Spec-conformance guards for the sessions value classes.
 *
 * These classes hold the raw wire values sent to and returned by the API, so a typo in a value is
 * invisible at development time and only fails against the live API. Each class below is asserted
 * against the exact value set defined by the Checkout.com API Reference, plus a structural guard
 * that catches casing mistakes across every value class in the Sessions namespace.
 */
class SessionsValueClassesTest extends TestCase
{
    private static function activeValues(string $class): array
    {
        $reflection = new ReflectionClass($class);
        $values = [];

        foreach ($reflection->getProperties() as $property) {
            $doc = $property->getDocComment();
            if ($doc !== false && strpos($doc, "@deprecated") !== false) {
                continue;
            }
            $values[] = $property->getValue();
        }

        return $values;
    }

    /**
     * Spec: Category enum is ["payment", "non_payment"]. Guards against the camelCase "nonPayment",
     * which the API rejects.
     */
    public function testCategoryMatchesSpec()
    {
        $this->assertSame(["payment", "non_payment"], self::activeValues(Category::class));
    }

    /**
     * Spec: TransactionType enum is the five values below. Note the correct spelling is
     * "quasi_card_transaction", not "quasi_card_transaction".
     */
    public function testTransactionTypeMatchesSpec()
    {
        $this->assertSame([
            "account_funding",
            "check_acceptance",
            "goods_service",
            "prepaid_activation_and_load",
            "quasi_card_transaction",
        ], self::activeValues(TransactionType::class));
    }

    /**
     * Spec: the session scheme enum carries eight values. "discover" and "upi" were previously
     * missing.
     */
    public function testSessionSchemeMatchesSpec()
    {
        $expected = [
            "amex",
            "cartes_bancaires",
            "diners",
            "discover",
            "jcb",
            "mastercard",
            "upi",
            "visa",
        ];

        $actual = self::activeValues(SessionScheme::class);
        sort($actual);

        $this->assertSame($expected, $actual);
    }

    /**
     * Spec: AuthenticationType enum is the five values below.
     */
    public function testAuthenticationTypeMatchesSpec()
    {
        $expected = ["add_card", "installment", "maintain_card", "recurring", "regular"];

        $actual = self::activeValues(AuthenticationType::class);
        sort($actual);

        $this->assertSame($expected, $actual);
    }

    /**
     * Structural guard across every value class in the Sessions namespace: an API value must be
     * snake_case, or a single uppercase letter for the scheme-style codes (Y/N/U). This catches
     * camelCase leaking into a wire value, which is how "nonPayment" survived undetected.
     */
    public function testEveryValueIsSnakeCaseOrSingleUppercaseCode()
    {
        $checked = 0;

        foreach (self::sessionsValueFiles() as $file) {
            $source = file_get_contents($file);

            // Skip model classes; only value classes (static properties, no instance properties).
            if (preg_match('/public \$\w+;/', $source) === 1) {
                continue;
            }

            preg_match_all(
                '#(?:/\*\*(.*?)\*/\s*)?public static \$(\w+)\s*=\s*"([^"]+)"#s',
                $source,
                $matches,
                PREG_SET_ORDER
            );

            foreach ($matches as $match) {
                if (strpos($match[1], "@deprecated") !== false) {
                    continue;
                }

                $name = $match[2];
                $value = $match[3];
                $checked++;

                $this->assertMatchesRegularExpression(
                    '/^([a-z0-9_]+|[A-Z])$/',
                    $value,
                    sprintf('%s::$%s = "%s" is not a valid API value', basename($file, '.php'), $name, $value)
                );
            }
        }

        $this->assertGreaterThan(50, $checked, 'expected to check more than 50 session values');
    }

    private static function sessionsValueFiles(): array
    {
        $directory = new \RecursiveDirectoryIterator(__DIR__ . '/../../../../lib/Checkout/Sessions');
        $files = [];

        foreach (new \RecursiveIteratorIterator($directory) as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $files[] = $file->getPathname();
            }
        }

        return $files;
    }
}
