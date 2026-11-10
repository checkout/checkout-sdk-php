<?php

namespace Checkout\Tests;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutConfiguration;
use Checkout\Disputes\DisputesClient;
use Checkout\Disputes\DisputesQueryFilter;
use Checkout\Environment;
use Checkout\Events\Previous\EventsClient;
use Checkout\HttpClientBuilderInterface;
use Checkout\Instruments\Get\BankAccountFieldQuery;
use Checkout\Instruments\InstrumentsClient;
use Checkout\PlatformType;
use Checkout\SdkAuthorization;
use Checkout\SdkCredentialsInterface;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Psr7\Response;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Monolog\Handler\NullHandler;
use Monolog\Logger;
use Psr\Http\Message\RequestInterface;

/**
 * Request URLs built by the clients: every caller value is sent as exactly one path segment or one
 * query value, checked on the request that reaches the HTTP handler.
 */
class RequestBuildingTest extends MockeryTestCase
{
    /**
     * @var RequestInterface[] every request that reached the handler
     */
    private $requests = [];

    /**
     * @var CheckoutConfiguration
     */
    private $configuration;

    /**
     * @var ApiClient
     */
    private $apiClient;

    /**
     * @before
     */
    public function init()
    {
        $this->requests = [];
        $test = $this;
        $stack = new HandlerStack(function (RequestInterface $request) use ($test) {
            $test->captureRequest($request);
            return new FulfilledPromise(new Response(200, ['Content-Type' => 'application/json'], '{"id":"res_123"}'));
        });
        $builder = $this->createMock(HttpClientBuilderInterface::class);
        $builder->method('getClient')->willReturn(new Client(['handler' => $stack]));
        $credentials = $this->createMock(SdkCredentialsInterface::class);
        $credentials->method('getAuthorization')
            ->willReturn(new SdkAuthorization(PlatformType::$default, 'sk_test_xxx'));
        $logger = new Logger('checkout-sdk-test-php');
        $logger->pushHandler(new NullHandler());
        $this->configuration = new CheckoutConfiguration($credentials, Environment::sandbox(), $builder, $logger);
        $this->apiClient = new ApiClient($this->configuration);
    }

    /**
     * @param RequestInterface $request a request that reached the handler
     */
    public function captureRequest(RequestInterface $request)
    {
        $this->requests[] = $request;
    }

    /**
     * @return DisputesClient
     */
    private function disputesClient()
    {
        return new DisputesClient($this->apiClient, $this->configuration, AuthorizationType::$secretKey);
    }

    /**
     * @return RequestInterface the only request sent
     */
    private function onlyRequest()
    {
        $this->assertCount(1, $this->requests);
        return $this->requests[0];
    }

    /**
     * @test
     */
    public function callerValueWithQueryCharactersStaysInOnePathSegment()
    {
        $this->disputesClient()->accept("x..y?z=1&w#v%");

        $request = $this->onlyRequest();
        $this->assertSame("POST", $request->getMethod());
        $this->assertSame("/disputes/x..y%3Fz%3D1%26w%23v%25/accept", $request->getUri()->getPath());
        $this->assertSame("", $request->getUri()->getQuery());
    }

    /**
     * @return array[]
     */
    public static function validSegments()
    {
        return [
            "dispute id" => ["dsp_rbhwd2qrg13uhrp2newf", "dsp_rbhwd2qrg13uhrp2newf"],
            "unreserved characters" => ["a-B.c_d~9", "a-B.c_d~9"],
            "integer" => [12345, "12345"],
            "zero" => ["0", "0"],
            "space" => ["a b", "a%20b"],
            "percent" => ["%2e%2e", "%252e%252e"],
            "unicode" => ["é", "%C3%A9"],
            "three dots" => ["...", "..."],
        ];
    }

    /**
     * @test
     * @dataProvider validSegments
     * @param mixed $segment
     * @param string $expected
     */
    public function pathSegmentIsPercentEncoded($segment, $expected)
    {
        $this->disputesClient()->accept($segment);

        $this->assertSame("/disputes/" . $expected . "/accept", $this->onlyRequest()->getUri()->getPath());
    }

    /**
     * @return array[]
     */
    public static function invalidSegments()
    {
        return [
            "empty" => [""],
            "dot" => ["."],
            "dot dot" => [".."],
            "null" => [null],
            "boolean" => [true],
            "float" => [1.5],
            "array" => [["dsp_1"]],
            "object" => [new \stdClass()],
            "slash" => ["a/b"],
            "dot dot slash" => ["../x"],
            "backslash" => ["a\\b"],
            "NUL" => ["a\0b"],
            "CR LF" => ["a\r\nb"],
            "tab" => ["a\tb"],
            "unit separator" => ["a\x1Fb"],
            "DEL" => ["a\x7Fb"],
            "invalid UTF-8" => ["\xC0\xAE"],
            "lone continuation byte" => ["a\x80"],
        ];
    }

    /**
     * @test
     * @dataProvider invalidSegments
     * @param mixed $segment
     */
    public function invalidPathSegmentIsRejectedBeforeAnyRequest($segment)
    {
        try {
            $this->disputesClient()->accept($segment);
            $this->fail("CheckoutArgumentException expected");
        } catch (CheckoutArgumentException $e) {
            $this->assertSame([], $this->requests);
        }
    }

    /**
     * @test
     */
    public function queryFilterValuesStayInTheirParameter()
    {
        $filter = new DisputesQueryFilter();
        $filter->statuses = "a&entity_ids=x#b";
        $filter->entity_ids = "ent_1";

        $this->disputesClient()->query($filter);

        $request = $this->onlyRequest();
        $this->assertSame("/disputes", $request->getUri()->getPath());
        $this->assertSame("", $request->getUri()->getFragment());
        $query = $request->getUri()->getQuery();
        $this->assertSame("statuses=a%26entity_ids%3Dx%23b&entity_ids=ent_1", $query);
        parse_str($query, $parsed);
        $this->assertSame(["statuses" => "a&entity_ids=x#b", "entity_ids" => "ent_1"], $parsed);
    }

    /**
     * @test
     */
    public function eventTypesVersionStaysInItsParameter()
    {
        $client = new EventsClient($this->apiClient, $this->configuration);

        $client->retrieveAllEventTypes("2.0&x=1#y/../z");

        $request = $this->onlyRequest();
        $this->assertSame("/event-types", $request->getUri()->getPath());
        $this->assertSame("version=2.0%26x%3D1%23y%2F..%2Fz", $request->getUri()->getQuery());
        $this->assertSame("", $request->getUri()->getFragment());
    }

    /**
     * @test
     */
    public function eventTypesWithoutVersionSendsNoQuery()
    {
        $client = new EventsClient($this->apiClient, $this->configuration);

        $client->retrieveAllEventTypes();

        $this->assertSame("", $this->onlyRequest()->getUri()->getQuery());
    }

    /**
     * @test
     */
    public function eventTypesVersionZeroIsSent()
    {
        $client = new EventsClient($this->apiClient, $this->configuration);

        $client->retrieveAllEventTypes("0");

        $this->assertSame("version=0", $this->onlyRequest()->getUri()->getQuery());
    }

    /**
     * @test
     */
    public function eventTypesEmptyVersionSendsNoQuery()
    {
        $client = new EventsClient($this->apiClient, $this->configuration);

        $client->retrieveAllEventTypes("");

        $this->assertSame("", $this->onlyRequest()->getUri()->getQuery());
    }

    /**
     * @test
     */
    public function bankAccountFieldQueryEncodesEachValue()
    {
        $query = new BankAccountFieldQuery();
        $query->account_holder_type = "a b~c&d=1#e";
        $client = new InstrumentsClient($this->apiClient, $this->configuration);

        $client->getBankAccountFieldFormatting("GB", "GBP", $query);

        $request = $this->onlyRequest();
        $this->assertSame("/validation/bank-accounts/GB/GBP", $request->getUri()->getPath());
        $this->assertSame("account-holder-type=a%20b~c%26d%3D1%23e", $request->getUri()->getQuery());
    }

    /**
     * @return array[]
     */
    public static function invalidQueryValues()
    {
        return [
            "array" => [["a", "b&c"]],
            "object" => [new \stdClass()],
            "immutable date" => [new \DateTimeImmutable("2026-01-01T00:00:00+00:00")],
        ];
    }

    /**
     * @test
     * @dataProvider invalidQueryValues
     * @param mixed $value
     */
    public function queryValueThatIsNotScalarIsRejectedBeforeAnyRequest($value)
    {
        $filter = new DisputesQueryFilter();
        $filter->statuses = $value;

        try {
            $this->disputesClient()->query($filter);
            $this->fail("CheckoutArgumentException expected");
        } catch (CheckoutArgumentException $e) {
            $this->assertSame([], $this->requests);
        }
    }

    /**
     * @test
     * @dataProvider invalidQueryValues
     * @param mixed $value
     */
    public function bankAccountFieldQueryValueThatIsNotScalarIsRejectedBeforeAnyRequest($value)
    {
        $query = new BankAccountFieldQuery();
        $query->account_holder_type = $value;
        $client = new InstrumentsClient($this->apiClient, $this->configuration);

        try {
            $client->getBankAccountFieldFormatting("GB", "GBP", $query);
            $this->fail("CheckoutArgumentException expected");
        } catch (CheckoutArgumentException $e) {
            $this->assertSame([], $this->requests);
        }
    }

    /**
     * Every buildPath argument in lib/ is a class constant, a fixed literal, a fixed base path, or a
     * value passed through encodePathSegment, so a caller value can never reach a path unencoded.
     * Every request path is built by buildPath or is a constant, a literal or a $path built that way;
     * no path or query literal is concatenated with a variable; functions that can build a URL from
     * pieces appear only where listed; query strings are built only by CheckoutUtils::buildQuery.
     *
     * @test
     */
    public function libraryEncodesEveryCallerValueInRequestUrls()
    {
        $libDir = dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . 'lib';
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($libDir, \FilesystemIterator::SKIP_DOTS)
        );
        $allowedArgument = '/^(self::[A-Z][A-Z0-9_]*|\'[^\'\\\\]*\'|"[^"\\\\$]*"|\$this->getBaseUrl\(\)'
            . '|\$this->encodePathSegment\(\$[A-Za-z_][A-Za-z0-9_]*\))$/';
        // ApiClient: "?" joins the output of AbstractQueryFilter, already encoded.
        // AccountsClient: Accept header value, not part of the URL.
        // EnvironmentSubdomain: host built from a subdomain checked against SUBDOMAIN_PATTERN.
        $notUrlOrAlreadyEncoded = [
            'ApiClient.php "?"',
            'AccountsClient.php "application/json;schema_version="',
            "EnvironmentSubdomain.php '://'",
        ];
        // First argument of every apiClient or filesApiClient request: built by buildPath, a constant,
        // a literal, a constant joined to a literal, or $path (whose assignments are checked below).
        // One balanced argument list, so a call must end where the argument or statement ends:
        // "$this->buildPath(...) . f($x)" does not match.
        $call = '(\((?:[^()"\'\\\\]++|"[^"\\\\]*"|\'[^\'\\\\]*\'|(?-1))*\))';
        $allowedRequestPath = '/^(\$this->buildPath' . $call . '|self::[A-Z][A-Z0-9_]*|"[^"\\\\$]*"|\'[^\'\\\\]*\''
            . '|self::[A-Z][A-Z0-9_]*\."[^"\\\\$]*"|\$path)$/s';
        $allowedPathAssignment = '/^(\$this->buildPath' . $call . '|"[^"\\\\$]*"'
            . '|"[^"\\\\$?]*\?"\.CheckoutUtils::buildQuery\(\[[^()]*\]\))$/s';
        $allowedPathAppend = '/^"\?"\.CheckoutUtils::buildQuery\(\[[^()]*\]\)$/s';
        // Functions that can build a URL out of pieces; each use outside these is reported. Query
        // strings go through CheckoutUtils::buildQuery.
        $stringBuilders = [
            'sprintf', 'vsprintf', 'implode', 'join', 'str_replace', 'strtr', 'substr_replace',
            'urlencode', 'rawurlencode', 'http_build_query', 'preg_replace', 'preg_replace_callback',
        ];
        $allowedStringBuilders = [
            'CheckoutApiException.php sprintf',     // exception message
            'ApiClient.php implode',                // header name casing
            'OAuthSdkCredentials.php implode',      // OAuth scope list in the token request body
            'Client.php join',                      // buildPath
            'Client.php rawurlencode',              // encodePathSegment
            'Client.php http_build_query',          // form-urlencoded request body
            'CheckoutUtils.php http_build_query',   // buildQuery, the one query builder
            'Client.php preg_replace',              // camelCase to snake_case for the form body keys
        ];
        // Functions that build or encode request URLs may only be declared where they are today.
        $ownedFunctions = [
            'buildpath' => ['Client.php'],
            'encodepathsegment' => ['Client.php'],
            'buildquery' => ['CheckoutUtils.php'],
            'renderqueryvalue' => ['CheckoutUtils.php'],
            'getbaseurl' => ['KlarnaClient.php'],
            'getencodedqueryparameters' => ['AbstractQueryFilter.php'],
        ];
        $offending = [];
        $calls = 0;
        $requests = 0;
        foreach ($files as $file) {
            $tokens = $this->normalisedTokens(file_get_contents($file->getPathname()));
            $fileName = $file->getFilename();
            $count = count($tokens);
            for ($i = 0; $i < $count; $i++) {
                $token = $tokens[$i];
                if (!is_array($token)) {
                    continue;
                }
                if ($token[0] === T_STRING && strcasecmp($token[1], 'buildPath') === 0
                    && isset($tokens[$i + 1]) && $tokens[$i + 1] === '('
                    && !(is_array($tokens[$i - 1]) && $tokens[$i - 1][0] === T_FUNCTION)
                ) {
                    $calls++;
                    foreach ($this->callArguments($tokens, $i + 1) as $argument) {
                        if (preg_match($allowedArgument, $argument) !== 1
                            || $argument === '$this->getBaseUrl()' && $fileName !== 'KlarnaClient.php'
                        ) {
                            $offending[] = $fileName . ':' . $token[2] . ' buildPath argument ' . $argument;
                        }
                    }
                }
                if ($token[0] === T_CONSTANT_ENCAPSED_STRING
                    && preg_match('/[\/?=&#]/', substr($token[1], 1, -1)) === 1
                    && $this->concatenatedWithVariable($tokens, $i)
                    && !in_array($fileName . ' ' . $token[1], $notUrlOrAlreadyEncoded, true)
                ) {
                    $offending[] = $fileName . ':' . $token[2] . ' ' . $token[1] . ' concatenated with a variable';
                }
                if ($token[0] === T_ENCAPSED_AND_WHITESPACE && preg_match('/[\/?=&#]/', $token[1]) === 1) {
                    $offending[] = $fileName . ':' . $token[2] . ' interpolated string ' . $token[1];
                }
                if ($token[0] === T_START_HEREDOC) {
                    $offending[] = $fileName . ':' . $token[2] . ' heredoc';
                }
                if ($token[0] === T_STRING && in_array($token[1], ['apiClient', 'filesApiClient'], true)
                    && $this->isTokenOf($tokens, $i - 1, T_OBJECT_OPERATOR)
                    && $this->isTokenOf($tokens, $i + 1, T_OBJECT_OPERATOR)
                    && isset($tokens[$i + 3]) && $tokens[$i + 3] === '('
                ) {
                    $requests++;
                    $arguments = $this->callArguments($tokens, $i + 3);
                    if (!isset($arguments[0]) || preg_match($allowedRequestPath, $arguments[0]) !== 1) {
                        $offending[] = $fileName . ':' . $token[2] . ' request path '
                            . (isset($arguments[0]) ? $arguments[0] : '(none)');
                    }
                }
                if ($token[0] === T_VARIABLE && $token[1] === '$path' && $fileName !== 'ApiClient.php'
                    && isset($tokens[$i + 1])
                    && ($tokens[$i + 1] === '=' || $this->isTokenOf($tokens, $i + 1, T_CONCAT_EQUAL))
                ) {
                    $pattern = $tokens[$i + 1] === '=' ? $allowedPathAssignment : $allowedPathAppend;
                    $value = $this->statementText($tokens, $i + 2);
                    if (preg_match($pattern, $value) !== 1) {
                        $offending[] = $fileName . ':' . $token[2] . ' $path assigned ' . $value;
                    }
                }
                if ($token[0] === T_VARIABLE && $token[1] === '$path' && $fileName !== 'ApiClient.php'
                    && !(isset($tokens[$i + 1])
                        && ($tokens[$i + 1] === '=' || $this->isTokenOf($tokens, $i + 1, T_CONCAT_EQUAL)))
                    && !$this->isRequestPathArgument($tokens, $i)
                ) {
                    $offending[] = $fileName . ':' . $token[2] . ' $path used outside a checked assignment or request';
                }
                if ($token[0] === T_STRING && in_array($token[1], ['apiClient', 'filesApiClient'], true)
                    && $this->isTokenOf($tokens, $i - 1, T_OBJECT_OPERATOR)
                    && !$this->isTokenOf($tokens, $i + 1, T_OBJECT_OPERATOR)
                    && !(isset($tokens[$i + 1]) && $tokens[$i + 1] === '=')
                ) {
                    $offending[] = $fileName . ':' . $token[2] . ' ' . $token[1] . ' read as a value';
                }
                if ($token[0] === T_STRING && $this->isTokenOf($tokens, $i - 1, T_FUNCTION)
                    && isset($ownedFunctions[strtolower($token[1])])
                    && !in_array($fileName, $ownedFunctions[strtolower($token[1])], true)
                ) {
                    $offending[] = $fileName . ':' . $token[2] . ' declares ' . $token[1];
                }
                if ($token[0] === T_STRING && isset($tokens[$i + 1]) && $tokens[$i + 1] === '('
                    && in_array(strtolower($token[1]), $stringBuilders, true)
                    && !$this->isTokenOf($tokens, $i - 1, T_OBJECT_OPERATOR)
                    && !$this->isTokenOf($tokens, $i - 1, T_DOUBLE_COLON)
                    && !$this->isTokenOf($tokens, $i - 1, T_FUNCTION)
                ) {
                    $use = $fileName . ' ' . strtolower($token[1]);
                    if (!in_array($use, $allowedStringBuilders, true)) {
                        $offending[] = $fileName . ':' . $token[2] . ' ' . $token[1] . '()';
                    }
                }
            }
        }

        $this->assertGreaterThan(100, $calls);
        $this->assertGreaterThan(100, $requests);
        $this->assertSame([], $offending);
    }

    /**
     * Tokens without whitespace and comments; "?->" is read as "->" and qualified names (such as
     * \sprintf) as their last part, so both spellings are checked the same way.
     *
     * @param string $source PHP source
     * @return array
     */
    private function normalisedTokens($source)
    {
        $qualified = [];
        foreach (['T_NAME_QUALIFIED', 'T_NAME_FULLY_QUALIFIED', 'T_NAME_RELATIVE'] as $name) {
            if (defined($name)) {
                $qualified[] = constant($name);
            }
        }
        $nullsafe = defined('T_NULLSAFE_OBJECT_OPERATOR') ? constant('T_NULLSAFE_OBJECT_OPERATOR') : null;
        $tokens = [];
        foreach (token_get_all($source) as $token) {
            if (is_array($token) && in_array($token[0], [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT], true)) {
                continue;
            }
            if (is_array($token) && $token[0] === $nullsafe) {
                $token = [T_OBJECT_OPERATOR, '->', $token[2]];
            } elseif (is_array($token) && in_array($token[0], $qualified, true)) {
                $token = [T_STRING, substr(strrchr('\\' . $token[1], '\\'), 1), $token[2]];
            }
            $tokens[] = $token;
        }
        return $tokens;
    }

    /**
     * @param array $tokens tokens without whitespace and comments
     * @param int $i index of a variable
     * @return bool whether the variable is the whole first argument of an apiClient or filesApiClient call
     */
    private function isRequestPathArgument(array $tokens, $i)
    {
        return isset($tokens[$i + 1]) && $tokens[$i + 1] === ','
            && $tokens[$i - 1] === '('
            && $this->isTokenOf($tokens, $i - 2, T_STRING)
            && $this->isTokenOf($tokens, $i - 3, T_OBJECT_OPERATOR)
            && $this->isTokenOf($tokens, $i - 4, T_STRING)
            && in_array($tokens[$i - 4][1], ['apiClient', 'filesApiClient'], true);
    }

    /**
     * @param array $tokens tokens without whitespace and comments
     * @param int $i index to check
     * @param int $type token type
     * @return bool whether the token at $i has the given type
     */
    private function isTokenOf(array $tokens, $i, $type)
    {
        return isset($tokens[$i]) && is_array($tokens[$i]) && $tokens[$i][0] === $type;
    }

    /**
     * @param array $tokens tokens without whitespace and comments
     * @param int $start index of the first token of the expression
     * @return string the source text up to the end of the statement, without whitespace
     */
    private function statementText(array $tokens, $start)
    {
        $text = '';
        $count = count($tokens);
        for ($i = $start; $i < $count && $tokens[$i] !== ';'; $i++) {
            $text .= is_array($tokens[$i]) ? $tokens[$i][1] : $tokens[$i];
        }
        return $text;
    }

    /**
     * @param array $tokens tokens without whitespace and comments
     * @param int $open index of the opening parenthesis
     * @return string[] each top-level argument as source text without whitespace
     */
    private function callArguments(array $tokens, $open)
    {
        $arguments = [];
        $current = '';
        $depth = 0;
        $count = count($tokens);
        for ($i = $open; $i < $count; $i++) {
            $text = is_array($tokens[$i]) ? $tokens[$i][1] : $tokens[$i];
            if (in_array($text, ['(', '[', '{'], true)) {
                $depth++;
                if ($depth === 1) {
                    continue;
                }
            } elseif (in_array($text, [')', ']', '}'], true)) {
                $depth--;
                if ($depth === 0) {
                    $arguments[] = $current;
                    break;
                }
            } elseif ($text === ',' && $depth === 1) {
                $arguments[] = $current;
                $current = '';
                continue;
            }
            $current .= $text;
        }
        return array_values(array_filter($arguments, function ($argument) {
            return $argument !== '';
        }));
    }

    /**
     * @param array $tokens tokens without whitespace and comments
     * @param int $i index of a string literal
     * @return bool whether the literal is joined with "." to anything other than a literal, a class
     *     constant or the result of an encoder (CheckoutUtils::buildQuery, urlencode, rawurlencode,
     *     http_build_query)
     */
    private function concatenatedWithVariable(array $tokens, $i)
    {
        if ($i > 1 && $tokens[$i - 1] === '.') {
            $operand = $tokens[$i - 2];
            $isLiteral = is_array($operand) && $operand[0] === T_CONSTANT_ENCAPSED_STRING;
            $isConstant = is_array($operand) && $operand[0] === T_STRING
                && is_array($tokens[$i - 3]) && $tokens[$i - 3][0] === T_DOUBLE_COLON;
            if (!$isLiteral && !$isConstant) {
                return true;
            }
        }
        if (isset($tokens[$i + 2]) && $tokens[$i + 1] === '.') {
            $operand = $tokens[$i + 2];
            $next = isset($tokens[$i + 3]) ? $tokens[$i + 3] : null;
            $isLiteral = is_array($operand) && $operand[0] === T_CONSTANT_ENCAPSED_STRING;
            $isEncoder = is_array($operand) && $operand[0] === T_STRING && $next === '('
                && in_array(strtolower($operand[1]), ['urlencode', 'rawurlencode', 'http_build_query'], true)
                || is_array($operand) && $operand[1] === 'CheckoutUtils'
                && is_array($next) && $next[0] === T_DOUBLE_COLON
                && $this->isTokenOf($tokens, $i + 4, T_STRING) && $tokens[$i + 4][1] === 'buildQuery'
                && isset($tokens[$i + 5]) && $tokens[$i + 5] === '(';
            $isConstant = is_array($operand) && $operand[0] === T_STRING
                && is_array($next) && $next[0] === T_DOUBLE_COLON
                && !(isset($tokens[$i + 5]) && $tokens[$i + 5] === '(');
            if (!$isLiteral && !$isEncoder && !$isConstant) {
                return true;
            }
        }
        return false;
    }
}
