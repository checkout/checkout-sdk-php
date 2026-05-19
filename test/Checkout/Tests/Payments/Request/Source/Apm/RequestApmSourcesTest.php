<?php

namespace Checkout\Tests\Payments\Request\Source\Apm;

use Checkout\Common\AccountHolder;
use Checkout\Common\Address;
use Checkout\Common\PaymentSourceType;
use Checkout\JsonSerializer;
use Checkout\Payments\BillingDescriptor;
use Checkout\Payments\Request\Source\Apm\RequestAchSource;
use Checkout\Payments\Request\Source\Apm\RequestAfterPaySource;
use Checkout\Payments\Request\Source\Apm\RequestAlipayCnSource;
use Checkout\Payments\Request\Source\Apm\RequestAlipayHkSource;
use Checkout\Payments\Request\Source\Apm\RequestAlipayPlusSource;
use Checkout\Payments\Request\Source\Apm\RequestAlmaSource;
use Checkout\Payments\Request\Source\Apm\RequestBancontactSource;
use Checkout\Payments\Request\Source\Apm\RequestBenefitSource;
use Checkout\Payments\Request\Source\Apm\RequestBizumSource;
use Checkout\Payments\Request\Source\Apm\RequestBlikSource;
use Checkout\Payments\Request\Source\Apm\RequestCvConnectSource;
use Checkout\Payments\Request\Source\Apm\RequestDanaSource;
use Checkout\Payments\Request\Source\Apm\RequestEpsSource;
use Checkout\Payments\Request\Source\Apm\RequestFawrySource;
use Checkout\Payments\Request\Source\Apm\RequestGcashSource;
use Checkout\Payments\Request\Source\Apm\RequestGiropaySource;
use Checkout\Payments\Request\Source\Apm\RequestIdealSource;
use Checkout\Payments\Request\Source\Apm\RequestIllicadoSource;
use Checkout\Payments\Request\Source\Apm\RequestKakaopaySource;
use Checkout\Payments\Request\Source\Apm\RequestKlarnaSource;
use Checkout\Payments\Request\Source\Apm\RequestKnetSource;
use Checkout\Payments\Request\Source\Apm\RequestMbwaySource;
use Checkout\Payments\Request\Source\Apm\RequestMobilePaySource;
use Checkout\Payments\Request\Source\Apm\RequestMultiBancoSource;
use Checkout\Payments\Request\Source\Apm\RequestOctopusPaySource;
use Checkout\Payments\Request\Source\Apm\RequestP24Source;
use Checkout\Payments\Request\Source\Apm\RequestPayNowSource;
use Checkout\Payments\Request\Source\Apm\RequestPayPalSource;
use Checkout\Payments\Request\Source\Apm\RequestPlaidSource;
use Checkout\Payments\Request\Source\Apm\RequestPostFinanceSource;
use Checkout\Payments\Request\Source\Apm\RequestQPaySource;
use Checkout\Payments\Request\Source\Apm\RequestSepaSource;
use Checkout\Payments\Request\Source\Apm\RequestSequraSource;
use Checkout\Payments\Request\Source\Apm\RequestSofortSource;
use Checkout\Payments\Request\Source\Apm\RequestStcPaySource;
use Checkout\Payments\Request\Source\Apm\RequestSwishSource;
use Checkout\Payments\Request\Source\Apm\RequestTamaraSource;
use Checkout\Payments\Request\Source\Apm\RequestTngSource;
use Checkout\Payments\Request\Source\Apm\RequestTruemoneySource;
use Checkout\Payments\Request\Source\Apm\RequestTrustlySource;
use Checkout\Payments\Request\Source\Apm\RequestTwintSource;
use Checkout\Payments\Request\Source\Apm\RequestVippsSource;
use Checkout\Payments\Request\Source\Apm\RequestWeChatPaySource;
use PHPUnit\Framework\TestCase;

class RequestApmSourcesTest extends TestCase
{
    /**
     * Every APM source whose constructor takes no arguments.
     * Format: 'caseName' => [fqcn, expected discriminator type, isNew]
     *
     * `RequestAlipayPlusSource` is excluded here because it uses static factory
     * methods rather than a parameterless constructor; it is covered separately.
     *
     * @return array
     */
    public function apmSourceProvider()
    {
        return [
            'afterpay'    => [RequestAfterPaySource::class,    PaymentSourceType::$afterpay,    false],
            'alma'        => [RequestAlmaSource::class,        PaymentSourceType::$alma,        false],
            'bancontact'  => [RequestBancontactSource::class,  PaymentSourceType::$bancontact,  false],
            'benefit'     => [RequestBenefitSource::class,     PaymentSourceType::$benefit,     false],
            'cvconnect'   => [RequestCvConnectSource::class,   PaymentSourceType::$cvconnect,   false],
            'eps'         => [RequestEpsSource::class,         PaymentSourceType::$eps,         false],
            'fawry'       => [RequestFawrySource::class,       PaymentSourceType::$fawry,       false],
            'giropay'     => [RequestGiropaySource::class,     PaymentSourceType::$giropay,     false],
            'ideal'       => [RequestIdealSource::class,       PaymentSourceType::$ideal,       false],
            'illicado'    => [RequestIllicadoSource::class,    PaymentSourceType::$illicado,    false],
            'klarna'      => [RequestKlarnaSource::class,      PaymentSourceType::$klarna,      false],
            'knet'        => [RequestKnetSource::class,        PaymentSourceType::$knet,        false],
            'mbway'       => [RequestMbwaySource::class,       PaymentSourceType::$mbway,       false],
            'multibanco'  => [RequestMultiBancoSource::class,  PaymentSourceType::$multibanco,  false],
            'p24'         => [RequestP24Source::class,         PaymentSourceType::$przelewy24,  false],
            'paypal'      => [RequestPayPalSource::class,      PaymentSourceType::$paypal,      false],
            'postfinance' => [RequestPostFinanceSource::class, PaymentSourceType::$postfinance, false],
            'qpay'        => [RequestQPaySource::class,        PaymentSourceType::$qpay,        false],
            'sepa'        => [RequestSepaSource::class,        PaymentSourceType::$sepa,        false],
            'sofort'      => [RequestSofortSource::class,      PaymentSourceType::$sofort,      false],
            'stcpay'      => [RequestStcPaySource::class,      PaymentSourceType::$stcpay,      false],
            'tamara'      => [RequestTamaraSource::class,      PaymentSourceType::$tamara,      false],
            'trustly'     => [RequestTrustlySource::class,     PaymentSourceType::$trustly,     false],
            'wechatpay'   => [RequestWeChatPaySource::class,   PaymentSourceType::$wechatpay,   false],
            'ach'         => [RequestAchSource::class,         PaymentSourceType::$ach,         true],
            'alipay_cn'   => [RequestAlipayCnSource::class,    PaymentSourceType::$alipay_cn,   true],
            'alipay_hk'   => [RequestAlipayHkSource::class,    PaymentSourceType::$alipay_hk,   true],
            'bizum'       => [RequestBizumSource::class,       PaymentSourceType::$bizum,       true],
            'blik'        => [RequestBlikSource::class,        PaymentSourceType::$blik,        true],
            'dana'        => [RequestDanaSource::class,        PaymentSourceType::$dana,        true],
            'gcash'       => [RequestGcashSource::class,       PaymentSourceType::$gcash,       true],
            'kakaopay'    => [RequestKakaopaySource::class,    PaymentSourceType::$kakaopay,    true],
            'mobilepay'   => [RequestMobilePaySource::class,   PaymentSourceType::$mobilepay,   true],
            'octopus'     => [RequestOctopusPaySource::class,  PaymentSourceType::$octopus,     true],
            'paynow'      => [RequestPayNowSource::class,      PaymentSourceType::$paynow,      true],
            'plaid'       => [RequestPlaidSource::class,       PaymentSourceType::$plaid,       true],
            'sequra'      => [RequestSequraSource::class,      PaymentSourceType::$sequra,      true],
            'swish'       => [RequestSwishSource::class,       PaymentSourceType::$swish,       true],
            'tng'         => [RequestTngSource::class,         PaymentSourceType::$tng,         true],
            'truemoney'   => [RequestTruemoneySource::class,   PaymentSourceType::$truemoney,   true],
            'twint'       => [RequestTwintSource::class,       PaymentSourceType::$twint,       true],
            'vipps'       => [RequestVippsSource::class,       PaymentSourceType::$vipps,       true],
        ];
    }

    /**
     * @dataProvider apmSourceProvider
     */
    public function testConstructorSetsDiscriminatorType($fqcn, $expectedType)
    {
        $source = new $fqcn();

        $this->assertTrue(property_exists($source, 'type'), "$fqcn must expose a public \$type property");
        $this->assertSame($expectedType, $source->type);
    }

    /**
     * @dataProvider apmSourceProvider
     */
    public function testMinimalPayloadSerializesWithOnlyType($fqcn, $expectedType)
    {
        $source = new $fqcn();
        $json = (new JsonSerializer())->serialize($source);
        $decoded = json_decode($json, true);

        $this->assertTrue(is_array($decoded), "JSON output should decode to an array, got: $json");
        $this->assertSame($expectedType, $decoded['type']);
        $this->assertCount(
            1,
            $decoded,
            "Minimal payload for '$fqcn' must contain only the type discriminator, got: $json"
        );
    }

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public function alipayPlusFactoryProvider()
    {
        return [
            'alipay_plus' => ['requestAlipayPlusSource',          PaymentSourceType::$alipay_plus],
            'alipay_cn'   => ['requestAlipayPlusCNSource',        PaymentSourceType::$alipay_cn],
            'gcash'       => ['requestAlipayPlusGCashSource',     PaymentSourceType::$gcash],
            'alipay_hk'   => ['requestAlipayPlusHKSource',        PaymentSourceType::$alipay_hk],
            'dana'        => ['requestAlipayPlusDanaSource',      PaymentSourceType::$dana],
            'kakaopay'    => ['requestAlipayPlusKakaoPaySource',  PaymentSourceType::$kakaopay],
            'truemoney'   => ['requestAlipayPlusTrueMoneySource', PaymentSourceType::$truemoney],
            'tng'         => ['requestAlipayPlusTNGSource',       PaymentSourceType::$tng],
        ];
    }

    /**
     * @dataProvider alipayPlusFactoryProvider
     */
    public function testAlipayPlusFactoriesProduceCorrectDiscriminator($factoryMethod, $expectedType)
    {
        $source = RequestAlipayPlusSource::{$factoryMethod}();

        $this->assertInstanceOf(RequestAlipayPlusSource::class, $source);
        $this->assertSame($expectedType, $source->type);

        $decoded = json_decode((new JsonSerializer())->serialize($source), true);
        $this->assertSame($expectedType, $decoded['type']);
    }

    // -------------------------------------------------------------------------
    // Field-level tests for sources with non-trivial structures
    // -------------------------------------------------------------------------

    public function testAchSourceSerializesAllFields()
    {
        $accountHolder = new AccountHolder();
        $accountHolder->first_name = 'John';
        $accountHolder->last_name = 'Smith';

        $source = new RequestAchSource();
        $source->account_type = 'savings';
        $source->country = 'US';
        $source->account_number = '136549956';
        $source->bank_code = '021000021';
        $source->account_holder = $accountHolder;

        $decoded = json_decode((new JsonSerializer())->serialize($source), true);

        $this->assertSame('ach', $decoded['type']);
        $this->assertSame('savings', $decoded['account_type']);
        $this->assertSame('US', $decoded['country']);
        $this->assertSame('136549956', $decoded['account_number']);
        $this->assertSame('021000021', $decoded['bank_code']);
        $this->assertSame(['first_name' => 'John', 'last_name' => 'Smith'], $decoded['account_holder']);
    }

    public function testBlikSourceSerializesPartnerAgreementId()
    {
        $source = new RequestBlikSource();
        $source->partner_agreement_id = 'blik_payid_123456789';

        $decoded = json_decode((new JsonSerializer())->serialize($source), true);

        $this->assertSame('blik', $decoded['type']);
        $this->assertSame('blik_payid_123456789', $decoded['partner_agreement_id']);
    }

    public function testPlaidSourceSerializesTokenAndAccountHolder()
    {
        $accountHolder = new AccountHolder();
        $accountHolder->first_name = 'Jane';
        $accountHolder->last_name = 'Doe';

        $source = new RequestPlaidSource();
        $source->token = 'processor-sandbox-abc123';
        $source->account_holder = $accountHolder;

        $decoded = json_decode((new JsonSerializer())->serialize($source), true);

        $this->assertSame('plaid', $decoded['type']);
        $this->assertSame('processor-sandbox-abc123', $decoded['token']);
        $this->assertSame(['first_name' => 'Jane', 'last_name' => 'Doe'], $decoded['account_holder']);
    }

    public function testSequraSourceSerializesBillingAddress()
    {
        $address = new Address();
        $address->address_line1 = 'Calle Mayor 1';
        $address->city = 'Madrid';
        $address->state = 'Madrid';
        $address->zip = '28001';
        $address->country = 'ES';

        $source = new RequestSequraSource();
        $source->billing_address = $address;

        $decoded = json_decode((new JsonSerializer())->serialize($source), true);

        $this->assertSame('sequra', $decoded['type']);
        $this->assertSame('Calle Mayor 1', $decoded['billing_address']['address_line1']);
        $this->assertSame('Madrid', $decoded['billing_address']['city']);
        $this->assertSame('Madrid', $decoded['billing_address']['state']);
        $this->assertSame('28001', $decoded['billing_address']['zip']);
        $this->assertSame('ES', $decoded['billing_address']['country']);
    }

    public function testSwishSourceSerializesAccountHolderAndBillingDescriptor()
    {
        $accountHolder = new AccountHolder();
        $accountHolder->first_name = 'Anna';
        $accountHolder->last_name = 'Andersson';

        $billingDescriptor = new BillingDescriptor();
        $billingDescriptor->name = 'Swish Test';

        $source = new RequestSwishSource();
        $source->payment_country = 'SE';
        $source->account_holder = $accountHolder;
        $source->billing_descriptor = $billingDescriptor;

        $decoded = json_decode((new JsonSerializer())->serialize($source), true);

        $this->assertSame('swish', $decoded['type']);
        $this->assertSame('SE', $decoded['payment_country']);
        $this->assertSame(['first_name' => 'Anna', 'last_name' => 'Andersson'], $decoded['account_holder']);
        $this->assertSame(['name' => 'Swish Test'], $decoded['billing_descriptor']);
    }

    // -------------------------------------------------------------------------
    // Field-level tests for representative existing sources
    // -------------------------------------------------------------------------

    public function testSepaSourceSerializesAllFields()
    {
        $accountHolder = new AccountHolder();
        $accountHolder->first_name = 'Marie';
        $accountHolder->last_name = 'Curie';

        $source = new RequestSepaSource();
        $source->country = 'DE';
        $source->account_number = 'DE89370400440532013000';
        $source->bank_code = 'COBADEFFXXX';
        $source->currency = 'EUR';
        $source->mandate_id = 'mandate-123';
        $source->date_of_signature = '2026-01-01';
        $source->account_holder = $accountHolder;

        $decoded = json_decode((new JsonSerializer())->serialize($source), true);

        $this->assertSame('sepa', $decoded['type']);
        $this->assertSame('DE', $decoded['country']);
        $this->assertSame('DE89370400440532013000', $decoded['account_number']);
        $this->assertSame('COBADEFFXXX', $decoded['bank_code']);
        $this->assertSame('EUR', $decoded['currency']);
        $this->assertSame('mandate-123', $decoded['mandate_id']);
        $this->assertSame('2026-01-01', $decoded['date_of_signature']);
        $this->assertSame(['first_name' => 'Marie', 'last_name' => 'Curie'], $decoded['account_holder']);
    }

    public function testQPaySourceSerializesAllFields()
    {
        $source = new RequestQPaySource();
        $source->quantity = 1;
        $source->description = 'QPay test payment';
        $source->language = 'en';
        $source->national_id = 'ID-12345';

        $decoded = json_decode((new JsonSerializer())->serialize($source), true);

        $this->assertSame('qpay', $decoded['type']);
        $this->assertSame(1, $decoded['quantity']);
        $this->assertSame('QPay test payment', $decoded['description']);
        $this->assertSame('en', $decoded['language']);
        $this->assertSame('ID-12345', $decoded['national_id']);
    }

    public function testAlmaSourceSerializesBillingAddress()
    {
        $address = new Address();
        $address->address_line1 = '12 rue de Paris';
        $address->city = 'Paris';
        $address->country = 'FR';

        $source = new RequestAlmaSource();
        $source->billing_address = $address;

        $decoded = json_decode((new JsonSerializer())->serialize($source), true);

        $this->assertSame('alma', $decoded['type']);
        $this->assertSame('12 rue de Paris', $decoded['billing_address']['address_line1']);
        $this->assertSame('Paris', $decoded['billing_address']['city']);
        $this->assertSame('FR', $decoded['billing_address']['country']);
    }

    // -------------------------------------------------------------------------
    // Catch-all enum check
    // -------------------------------------------------------------------------

    public function testEnumConstantsExposeAllNewTypes()
    {
        $this->assertSame('ach', PaymentSourceType::$ach);
        $this->assertSame('bizum', PaymentSourceType::$bizum);
        $this->assertSame('blik', PaymentSourceType::$blik);
        $this->assertSame('mobilepay', PaymentSourceType::$mobilepay);
        $this->assertSame('octopus', PaymentSourceType::$octopus);
        $this->assertSame('paynow', PaymentSourceType::$paynow);
        $this->assertSame('plaid', PaymentSourceType::$plaid);
        $this->assertSame('sequra', PaymentSourceType::$sequra);
        $this->assertSame('swish', PaymentSourceType::$swish);
        $this->assertSame('twint', PaymentSourceType::$twint);
        $this->assertSame('vipps', PaymentSourceType::$vipps);
    }
}
