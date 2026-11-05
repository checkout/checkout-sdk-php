<?php

namespace Checkout\Tests;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutConfiguration;
use Checkout\Environment;
use Checkout\Files\FileRequest;
use Checkout\HttpClientBuilderInterface;
use Checkout\OAuthSdkCredentials;
use Checkout\PlatformType;
use Checkout\SdkAuthorization;
use Checkout\SdkCredentialsInterface;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Promise\Create;
use GuzzleHttp\Psr7\Response;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Monolog\Handler\NullHandler;
use Monolog\Logger;

/**
 * The SDK must leave transport options to the HTTP client: requests use Guzzle's defaults
 * unless the caller configures the client through a custom HttpClientBuilderInterface.
 */
class HttpClientOptionsTest extends MockeryTestCase
{
    /**
     * @var array[] options of every request that reached the handler
     */
    private $capturedOptions = [];

    /**
     * @param array $clientConfig extra Guzzle client configuration
     * @param string $responseBody body of the fake 200 response
     * @return Client
     */
    private function createCapturingClient(array $clientConfig = [], string $responseBody = '{"id":"res_123"}')
    {
        $this->capturedOptions = [];
        $stack = new HandlerStack(function ($request, array $options) use ($responseBody) {
            $this->capturedOptions[] = $options;
            return Create::promiseFor(
                new Response(200, ['Content-Type' => 'application/json'], $responseBody)
            );
        });
        return new Client(array_merge(['handler' => $stack], $clientConfig));
    }

    /**
     * @param Client $client
     * @return HttpClientBuilderInterface
     */
    private function createBuilder(Client $client)
    {
        $builder = $this->createMock(HttpClientBuilderInterface::class);
        $builder->method('getClient')->willReturn($client);
        return $builder;
    }

    /**
     * @param Client $client
     * @return ApiClient
     */
    private function createApiClient(Client $client)
    {
        $credentials = $this->createMock(SdkCredentialsInterface::class);
        $logger = new Logger('checkout-sdk-test-php');
        $logger->pushHandler(new NullHandler());
        return new ApiClient(new CheckoutConfiguration(
            $credentials,
            Environment::sandbox(),
            $this->createBuilder($client),
            $logger
        ));
    }

    /**
     * @return SdkAuthorization
     */
    private function authorization()
    {
        return new SdkAuthorization(PlatformType::$default, 'sk_test_xxx');
    }

    /**
     * @return FileRequest
     */
    private function fileRequest()
    {
        $fileRequest = new FileRequest();
        $fileRequest->file = __DIR__ . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR . 'checkout.jpeg';
        $fileRequest->purpose = 'dispute_evidence';
        return $fileRequest;
    }

    /**
     * @param string $body
     * @param array $clientConfig
     * @return OAuthSdkCredentials
     */
    private function initOAuth(string $body, array $clientConfig = [])
    {
        return OAuthSdkCredentials::init(
            $this->createBuilder($this->createCapturingClient($clientConfig, $body)),
            'client_id',
            'client_secret',
            'https://access.sandbox.checkout.com/connect/token',
            ['gateway']
        );
    }

    /**
     * TLS options belong to the HTTP client the merchant configures. The library may not
     * name any request option, cURL, stream or socket setting, CA override or transport that
     * could change or bypass them, and may only use the Guzzle classes listed below. Every
     * file under lib/ must be PHP and is tokenized; comments are skipped, string literals are
     * decoded and trimmed, and names are compared in full and by their last part. This guards
     * against reintroducing such code; deliberately obfuscated code is left to review, and
     * the runtime tests above cover the options actually sent.
     *
     * @test
     */
    public function libraryNeverSetsTlsVerificationOptions()
    {
        $libDir = dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . 'lib';
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($libDir, \FilesystemIterator::SKIP_DOTS)
        );
        $forbiddenStrings = [
            'verify', 'verify_peer', 'verify_peer_name', 'verify_host', 'peer_name', 'peer_fingerprint',
            'allow_self_signed', 'crypto_method', 'ssl_method', 'cafile', 'capath', 'local_cert',
            'local_pk', 'cert', 'ssl_cert', 'certificate', 'ssl_key', 'sslkey', 'curl', 'stream_context',
            'ssl', 'handler', 'proxy',
        ];
        $forbiddenStringPattern = '/^curlopt_|^curle_|ssl_cert_file|curl_ca_bundle|openssl\.ca|curl\.ca'
            . '|^(tls|ssl|sslv\d|tlsv[\d.]+):\/\//';
        $forbiddenNames = '/^(VERIFY|CURLOPT_\w+|CURLE_\w+|curl_\w+|stream_context_\w+|stream_socket_\w+'
            . '|STREAM_CRYPTO_METHOD_\w+|SOAP_SSL_METHOD_\w+|fsockopen|pfsockopen|file_get_contents'
            . '|SoapClient|setDefaultOption|chooseHandler|HandlerStack|CurlHandler|CurlMultiHandler'
            . '|RequestOptions|constant|ini_set|ini_alter|putenv|stream_wrapper_\\w+|libxml_set_streams_context)$/i';
        $allowedGuzzle = [
            'guzzlehttp\\exception\\requestexception',
            'guzzlehttp\\psr7\\response',
            'guzzlehttp\\psr7\\stream',
        ];
        $nameTokens = [T_STRING, T_NS_SEPARATOR];
        foreach (['T_NAME_QUALIFIED', 'T_NAME_FULLY_QUALIFIED', 'T_NAME_RELATIVE'] as $name) {
            if (defined($name)) {
                $nameTokens[] = constant($name);
            }
        }
        $offending = [];
        $count = 0;
        foreach ($files as $file) {
            $count++;
            $fileName = $file->getFilename();
            if (strtolower($file->getExtension()) !== 'php') {
                $offending[] = $fileName . ' is not a PHP file';
                continue;
            }
            $tokens = token_get_all(file_get_contents($file->getPathname()));
            $tokens[] = ';';
            $name = '';
            foreach ($tokens as $token) {
                if (is_array($token) && in_array($token[0], $nameTokens, true)) {
                    $name .= $token[1];
                    $lastPart = substr(strrchr('\\' . $token[1], '\\'), 1);
                    if (preg_match($forbiddenNames, $lastPart) === 1) {
                        $offending[] = $fileName . ':' . $token[2] . ' ' . $token[1];
                    }
                    continue;
                }
                $qualified = strtolower(ltrim($name, '\\'));
                $name = '';
                if ($qualified === 'guzzlehttp'
                    || strpos($qualified, 'guzzlehttp\\') === 0 && !in_array($qualified, $allowedGuzzle, true)
                    && !($qualified === 'guzzlehttp\\client' && $fileName === 'DefaultHttpClientBuilder.php')
                ) {
                    $offending[] = $fileName . ' ' . $qualified;
                }
                if (!is_array($token)) {
                    continue;
                }
                if ($token[0] === T_CONSTANT_ENCAPSED_STRING || $token[0] === T_ENCAPSED_AND_WHITESPACE) {
                    $value = strtolower(trim(stripcslashes(trim(ltrim($token[1], 'bB'), "'\""))));
                    if (in_array($value, $forbiddenStrings, true)
                        || preg_match($forbiddenStringPattern, $value) === 1
                    ) {
                        $offending[] = $fileName . ':' . $token[2] . ' ' . $token[1];
                    }
                }
            }
        }

        $this->assertGreaterThan(100, $count);
        $this->assertSame([], $offending);
    }

    /**
     * @test
     */
    public function apiRequestsUseClientDefaultVerifyOption()
    {
        $apiClient = $this->createApiClient($this->createCapturingClient());

        $apiClient->get('payments/pay_123', $this->authorization());
        $apiClient->post('payments', ['amount' => 100], $this->authorization(), null);
        $apiClient->submitFile('files', $this->fileRequest(), $this->authorization());

        $this->assertCount(3, $this->capturedOptions);
        foreach ($this->capturedOptions as $options) {
            $this->assertArrayHasKey('verify', $options);
            $this->assertTrue($options['verify']);
        }
    }

    /**
     * @test
     */
    public function apiRequestsHonourVerifyConfiguredOnCustomClient()
    {
        $caBundle = '/path/to/custom-ca.pem';
        $apiClient = $this->createApiClient($this->createCapturingClient(['verify' => $caBundle]));

        $apiClient->get('payments/pay_123', $this->authorization());
        $apiClient->submitFile('files', $this->fileRequest(), $this->authorization());

        $this->assertCount(2, $this->capturedOptions);
        foreach ($this->capturedOptions as $options) {
            $this->assertSame($caBundle, $options['verify']);
        }
    }

    /**
     * @test
     */
    public function tokenRequestUsesClientDefaultVerifyOption()
    {
        $this->initOAuth('{"access_token":"tok","token_type":"Bearer","expires_in":3600}');

        $this->assertCount(1, $this->capturedOptions);
        $this->assertTrue($this->capturedOptions[0]['verify']);
    }

    /**
     * @test
     */
    public function tokenRequestHonoursVerifyConfiguredOnCustomClient()
    {
        $caBundle = '/path/to/custom-ca.pem';
        $this->initOAuth(
            '{"access_token":"tok","token_type":"Bearer","expires_in":3600}',
            ['verify' => $caBundle]
        );

        $this->assertSame($caBundle, $this->capturedOptions[0]['verify']);
    }

    /**
     * @return array
     */
    public static function validTokenResponses()
    {
        return [
            'decimal expires_in' => ['{"access_token":"tok","token_type":"Bearer","expires_in":3599.5}'],
            'without token_type' => ['{"access_token":"tok","expires_in":3600}'],
            'expires_in of exactly one year' => ['{"access_token":"tok","token_type":"Bearer","expires_in":31536000}'],
        ];
    }

    /**
     * @test
     * @dataProvider validTokenResponses
     * @param string $body
     */
    public function validTokenResponseProducesAuthorization(string $body)
    {
        $credentials = $this->initOAuth($body);

        $authorization = $credentials->getAuthorization(AuthorizationType::$oAuth);

        $this->assertSame('Bearer tok', $authorization->getAuthorizationHeader());
    }

    /**
     * @return array
     */
    public static function invalidTokenResponses()
    {
        return [
            'not json' => ['<html>error</html>'],
            'json scalar' => ['"tok"'],
            'empty object' => ['{}'],
            'missing access_token' => ['{"token_type":"Bearer","expires_in":3600}'],
            'empty access_token' => ['{"access_token":"","token_type":"Bearer","expires_in":3600}'],
            'non string access_token' => ['{"access_token":123,"token_type":"Bearer","expires_in":3600}'],
            'missing expires_in' => ['{"access_token":"tok","token_type":"Bearer"}'],
            'non numeric expires_in' => ['{"access_token":"tok","token_type":"Bearer","expires_in":"soon"}'],
            'negative expires_in' => ['{"access_token":"tok","token_type":"Bearer","expires_in":-1}'],
            'expires_in above one year' => ['{"access_token":"tok","token_type":"Bearer","expires_in":31536001}'],
            'expires_in beyond int range' => ['{"access_token":"tok","token_type":"Bearer","expires_in":9e18}'],
            'expires_in float overflow' => ['{"access_token":"tok","token_type":"Bearer","expires_in":1e30}'],
            'numeric string expires_in' => ['{"access_token":"tok","token_type":"Bearer","expires_in":"3600"}'],
            'padded numeric string expires_in' => ['{"access_token":"tok","token_type":"Bearer","expires_in":" 3600"}'],
            'whitespace access_token' => ['{"access_token":" ","token_type":"Bearer","expires_in":3600}'],
            'access_token with space' => ['{"access_token":"to k","token_type":"Bearer","expires_in":3600}'],
            'access_token with CRLF' => ['{"access_token":"tok\\r\\nX: 1","token_type":"Bearer","expires_in":3600}'],
            'access_token with trailing LF' => ['{"access_token":"tok\\n","token_type":"Bearer","expires_in":3600}'],
            'access_token with NUL' => ['{"access_token":"tok\\u0000","token_type":"Bearer","expires_in":3600}'],
            'access_token with non ASCII' => ['{"access_token":"t\\u00f6k","token_type":"Bearer","expires_in":3600}'],
        ];
    }

    /**
     * @test
     * @dataProvider invalidTokenResponses
     * @param string $body
     */
    public function invalidTokenResponseThrowsAuthorizationException(string $body)
    {
        $this->expectException(CheckoutAuthorizationException::class);

        $this->initOAuth($body);
    }
}
