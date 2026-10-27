<?php

namespace Checkout\Tests\Payments\Setups;

use Checkout\Common\Address;
use Checkout\JsonSerializer;
use Checkout\Payments\Setups\Common\Billing\PaymentSetupBilling;
use Checkout\Payments\Setups\Common\PaymentMethods\Ach\Ach;
use Checkout\Payments\Setups\Common\PaymentMethods\Ach\AchAccountHolder;
use Checkout\Payments\Setups\Common\PaymentMethods\Ach\AchAccountHolderIdentification;
use Checkout\Payments\Setups\Common\PaymentMethods\Ach\AchAccountHolderType;
use Checkout\Payments\Setups\Common\PaymentMethods\Ach\PaymentSetupAchAccountType;
use Checkout\Payments\Setups\Common\PaymentMethods\AlipayCn\AlipayCn;
use Checkout\Payments\Setups\Common\PaymentMethods\AlipayHk\AlipayHk;
use Checkout\Payments\Setups\Common\PaymentMethods\Alma\Alma;
use Checkout\Payments\Setups\Common\PaymentMethods\Bancontact\Bancontact;
use Checkout\Payments\Setups\Common\PaymentMethods\Benefit\Benefit;
use Checkout\Payments\Setups\Common\PaymentMethods\Bizum\Bizum;
use Checkout\Payments\Setups\Common\PaymentMethods\Blik\Blik;
use Checkout\Payments\Setups\Common\PaymentMethods\CardPresent\CardPresent;
use Checkout\Payments\Setups\Common\PaymentMethods\CardPresent\CardPresentPin;
use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentMethodBase;
use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentMethodInitialization;
use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentMethodOption;
use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentMethodOptions;
use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentMethodStatus;
use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentSetupOsType;
use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentSetupPaymentMethod;
use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentSetupTerminalType;
use Checkout\Payments\Setups\Common\PaymentMethods\Dana\Dana;
use Checkout\Payments\Setups\Common\PaymentMethods\Eps\Eps;
use Checkout\Payments\Setups\Common\PaymentMethods\GCash\GCash;
use Checkout\Payments\Setups\Common\PaymentMethods\Ideal\Ideal;
use Checkout\Payments\Setups\Common\PaymentMethods\KakaoPay\KakaoPay;
use Checkout\Payments\Setups\Common\PaymentMethods\Knet\Knet;
use Checkout\Payments\Setups\Common\PaymentMethods\Knet\KnetLanguage;
use Checkout\Payments\Setups\Common\PaymentMethods\MbWay\MbWay;
use Checkout\Payments\Setups\Common\PaymentMethods\MobilePay\MobilePay;
use Checkout\Payments\Setups\Common\PaymentMethods\Multibanco\Multibanco;
use Checkout\Payments\Setups\Common\PaymentMethods\Octopus\Octopus;
use Checkout\Payments\Setups\Common\PaymentMethods\P24\P24;
use Checkout\Payments\Setups\Common\PaymentMethods\P24\P24AccountHolder;
use Checkout\Payments\Setups\Common\PaymentMethods\PayByBank\PayByBank;
use Checkout\Payments\Setups\Common\PaymentMethods\PaymentMethods;
use Checkout\Payments\Setups\Common\PaymentMethods\PayNow\PayNow;
use Checkout\Payments\Setups\Common\PaymentMethods\Qpay\Qpay;
use Checkout\Payments\Setups\Common\PaymentMethods\Sepa\PaymentSetupSepaMandateType;
use Checkout\Payments\Setups\Common\PaymentMethods\Sepa\Sepa;
use Checkout\Payments\Setups\Common\PaymentMethods\Sepa\SepaAccountHolder;
use Checkout\Payments\Setups\Common\PaymentMethods\Sepa\SepaAccountHolderType;
use Checkout\Payments\Setups\Common\PaymentMethods\Sepa\SepaMandate;
use Checkout\Payments\Setups\Common\PaymentMethods\Sequra\Sequra;
use Checkout\Payments\Setups\Common\PaymentMethods\Stablecoin\Stablecoin;
use Checkout\Payments\Setups\Common\PaymentMethods\Swish\Swish;
use Checkout\Payments\Setups\Common\PaymentMethods\Swish\SwishAccountHolder;
use Checkout\Payments\Setups\Common\PaymentMethods\Tamara\Tamara;
use Checkout\Payments\Setups\Common\PaymentMethods\Tamara\TamaraRiskAssessment;
use Checkout\Payments\Setups\Common\PaymentMethods\Tng\Tng;
use Checkout\Payments\Setups\Common\PaymentMethods\TrueMoney\TrueMoney;
use Checkout\Payments\Setups\Common\PaymentMethods\Twint\Twint;
use Checkout\Payments\Setups\Common\PaymentMethods\Vipps\Vipps;
use Checkout\Payments\Setups\Common\PaymentMethods\WeChatPay\WeChatPay;
use Checkout\Payments\Setups\Common\PaymentType\PaymentSetupPaymentType;
use Checkout\Payments\Setups\Request\PaymentSetupRequest;
use DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Serialization of the Payment Setup payment methods, payment_type and billing against the spec.
 */
class PaymentSetupPaymentMethodsSerializationTest extends TestCase
{
    private function encode($value): string
    {
        return (new JsonSerializer())->serialize($value);
    }

    private function roundTrip($value): array
    {
        $serializer = new JsonSerializer();
        return $serializer->deserialize($serializer->serialize($value));
    }

    /**
     * Each payment method key of the spec, with the SDK class that models it. The key is the literal
     * wire key: the serializer uses the PHP property name unchanged.
     */
    public function paymentMethodKeys(): array
    {
        return [
            "paynow" => ["paynow", PayNow::class],
            "qpay" => ["qpay", Qpay::class],
            "eps" => ["eps", Eps::class],
            "ideal" => ["ideal", Ideal::class],
            "knet" => ["knet", Knet::class],
            "bancontact" => ["bancontact", Bancontact::class],
            "benefit" => ["benefit", Benefit::class],
            "blik" => ["blik", Blik::class],
            "vipps" => ["vipps", Vipps::class],
            "twint" => ["twint", Twint::class],
            "alipay_cn" => ["alipay_cn", AlipayCn::class],
            "alipay_hk" => ["alipay_hk", AlipayHk::class],
            "gcash" => ["gcash", GCash::class],
            "tng" => ["tng", Tng::class],
            "dana" => ["dana", Dana::class],
            "mobilepay" => ["mobilepay", MobilePay::class],
            "tamara" => ["tamara", Tamara::class],
            "mbway" => ["mbway", MbWay::class],
            "multibanco" => ["multibanco", Multibanco::class],
            "wechatpay" => ["wechatpay", WeChatPay::class],
            "kakaopay" => ["kakaopay", KakaoPay::class],
            "truemoney" => ["truemoney", TrueMoney::class],
            "octopus" => ["octopus", Octopus::class],
            "p24" => ["p24", P24::class],
            "alma" => ["alma", Alma::class],
            "swish" => ["swish", Swish::class],
            "sequra" => ["sequra", Sequra::class],
            "ach" => ["ach", Ach::class],
            "sepa" => ["sepa", Sepa::class],
        ];
    }

    /**
     * @dataProvider paymentMethodKeys
     */
    public function testSerializesEachNewPaymentMethodUnderItsLiteralKey(string $key, string $class)
    {
        $this->assertTrue(property_exists(PaymentMethods::class, $key), $key . " is not declared");

        $method = new $class();
        $method->status = PaymentMethodStatus::$ready;

        $paymentMethods = new PaymentMethods();
        $paymentMethods->{$key} = $method;

        $json = $this->encode($paymentMethods);

        $this->assertSame('{"' . $key . '":{"status":"ready"}}', $json);
        $this->assertInstanceOf(PaymentSetupPaymentMethod::class, $method);
    }

    public function testSerializesSingleWordKeysWithoutSplittingThem()
    {
        $declared = array_keys(get_class_vars(PaymentMethods::class));
        foreach (["gcash", "paynow", "mobilepay", "mbway", "wechatpay", "kakaopay", "truemoney", "alipay_cn"] as $key) {
            $this->assertContains($key, $declared);
        }

        $paymentMethods = new PaymentMethods();
        $paymentMethods->gcash = new GCash();
        $paymentMethods->gcash->terminal_type = PaymentSetupTerminalType::$web;
        $paymentMethods->paynow = new PayNow();
        $paymentMethods->paynow->flags = [];
        $paymentMethods->mobilepay = new MobilePay();
        $paymentMethods->mobilepay->flags = [];
        $paymentMethods->mbway = new MbWay();
        $paymentMethods->mbway->flags = [];
        $paymentMethods->wechatpay = new WeChatPay();
        $paymentMethods->wechatpay->flags = [];
        $paymentMethods->kakaopay = new KakaoPay();
        $paymentMethods->kakaopay->terminal_type = PaymentSetupTerminalType::$app;
        $paymentMethods->truemoney = new TrueMoney();
        $paymentMethods->truemoney->os_type = PaymentSetupOsType::$ios;
        $paymentMethods->alipay_cn = new AlipayCn();
        $paymentMethods->alipay_cn->os_type = PaymentSetupOsType::$android;

        $json = $this->encode($paymentMethods);

        $this->assertStringContainsString('"gcash":{"terminal_type":"web"}', $json);
        $this->assertStringContainsString('"paynow":{"flags":[]}', $json);
        $this->assertStringContainsString('"mobilepay":{"flags":[]}', $json);
        $this->assertStringContainsString('"mbway":{"flags":[]}', $json);
        $this->assertStringContainsString('"wechatpay":{"flags":[]}', $json);
        $this->assertStringContainsString('"kakaopay":{"terminal_type":"app"}', $json);
        $this->assertStringContainsString('"truemoney":{"os_type":"ios"}', $json);
        $this->assertStringContainsString('"alipay_cn":{"os_type":"android"}', $json);
        foreach (["g_cash", "pay_now", "mobile_pay", "mb_way", "we_chat_pay", "kakao_pay", "true_money"] as $split) {
            $this->assertStringNotContainsString('"' . $split . '"', $json);
        }
    }

    public function testEachNewClassDeclaresExactlyTheSpecProperties()
    {
        $base = ["status", "flags"];
        $wallet = array_merge($base, ["terminal_type", "os_type"]);
        $expected = [
            Qpay::class => array_merge($base, ["national_id", "description"]),
            Ideal::class => array_merge($base, ["description"]),
            Knet::class => array_merge($base, ["language"]),
            Bancontact::class => array_merge($base, ["account_holder_name"]),
            Multibanco::class => array_merge($base, ["account_holder_name"]),
            Blik::class => array_merge($base, ["partner_code"]),
            AlipayCn::class => $wallet,
            AlipayHk::class => $wallet,
            GCash::class => $wallet,
            Tng::class => $wallet,
            Dana::class => $wallet,
            KakaoPay::class => $wallet,
            TrueMoney::class => $wallet,
            Tamara::class => array_merge($base, ["initialization", "risk_assessment"]),
            TamaraRiskAssessment::class => [
                "premium_customer", "risk_score", "order_count_last24h", "linked_device_count",
            ],
            P24::class => array_merge($base, ["account_holder"]),
            P24AccountHolder::class => ["name", "email"],
            Swish::class => array_merge($base, ["billing_descriptor", "account_holder"]),
            SwishAccountHolder::class => ["first_name", "last_name"],
            Ach::class => array_merge(
                $base,
                ["account_type", "account_holder", "account_number", "bank_code", "country"]
            ),
            AchAccountHolder::class => [
                "type", "first_name", "last_name", "company_name", "date_of_birth", "identification",
            ],
            AchAccountHolderIdentification::class => ["type", "issuing_country", "number"],
            Sepa::class => array_merge($base, ["account_holder", "account_number", "country", "currency", "mandate"]),
            SepaAccountHolder::class => ["type", "first_name", "last_name", "company_name"],
            SepaMandate::class => ["id", "type", "date_of_signature"],
            PaymentSetupBilling::class => ["address"],
            Bizum::class => array_merge($base, ["initialization", "payment_method_options"]),
            PayByBank::class => array_merge($base, ["initialization", "bank_id", "action"]),
            Stablecoin::class => array_merge($base, ["initialization"]),
        ];
        foreach ($expected as $class => $properties) {
            $this->assertSame($properties, array_keys(get_object_vars(new $class())), $class);
        }
    }

    public function testMethodsWithoutOwnFieldsRoundTripStatusAndFlags()
    {
        $classes = [
            PayNow::class, Eps::class, Benefit::class, Vipps::class, Twint::class, MobilePay::class,
            MbWay::class, WeChatPay::class, Octopus::class, Alma::class, Sequra::class,
        ];
        foreach ($classes as $class) {
            $method = new $class();
            $method->status = PaymentMethodStatus::$action_required;
            $method->flags = ["payment_methods.property_required"];

            $this->assertSame(
                ["status" => "action_required", "flags" => ["payment_methods.property_required"]],
                $this->roundTrip($method),
                $class
            );
            $this->assertSame(["status", "flags"], array_keys(get_object_vars(new $class())), $class);
        }
    }

    public function testQpayRoundTripKeepsEveryProperty()
    {
        $qpay = new Qpay();
        $qpay->status = PaymentMethodStatus::$ready;
        $qpay->flags = [];
        $qpay->national_id = "21234567890";
        $qpay->description = "Order 1234";

        $this->assertSame(
            ["status" => "ready", "flags" => [], "national_id" => "21234567890", "description" => "Order 1234"],
            $this->roundTrip($qpay)
        );
    }

    public function testIdealRoundTripKeepsEveryProperty()
    {
        $ideal = new Ideal();
        $ideal->status = PaymentMethodStatus::$ready;
        $ideal->flags = [];
        $ideal->description = "2 t-shirts";

        $this->assertSame(
            ["status" => "ready", "flags" => [], "description" => "2 t-shirts"],
            $this->roundTrip($ideal)
        );
    }

    public function testKnetRoundTripKeepsEveryProperty()
    {
        $knet = new Knet();
        $knet->status = PaymentMethodStatus::$ready;
        $knet->flags = [];
        $knet->language = KnetLanguage::$ar;

        $this->assertSame(
            ["status" => "ready", "flags" => [], "language" => "ar"],
            $this->roundTrip($knet)
        );
    }

    public function testBancontactAndMultibancoRoundTripKeepEveryProperty()
    {
        foreach ([Bancontact::class, Multibanco::class] as $class) {
            $method = new $class();
            $method->status = PaymentMethodStatus::$ready;
            $method->flags = [];
            $method->account_holder_name = "John Smith";

            $this->assertSame(
                ["status" => "ready", "flags" => [], "account_holder_name" => "John Smith"],
                $this->roundTrip($method),
                $class
            );
        }
    }

    public function testBlikRoundTripKeepsEveryPropertyAndHasNoInitialization()
    {
        $blik = new Blik();
        $blik->status = PaymentMethodStatus::$ready;
        $blik->flags = [];
        $blik->partner_code = "999111";

        $this->assertSame(
            ["status" => "ready", "flags" => [], "partner_code" => "999111"],
            $this->roundTrip($blik)
        );
        $this->assertFalse(property_exists($blik, "initialization"));
        $this->assertNotInstanceOf(PaymentMethodBase::class, $blik);
    }

    public function testWalletsRoundTripTerminalAndOsType()
    {
        $classes = [
            AlipayCn::class, AlipayHk::class, GCash::class, Tng::class, Dana::class, KakaoPay::class,
            TrueMoney::class,
        ];
        foreach ($classes as $class) {
            $wallet = new $class();
            $wallet->status = PaymentMethodStatus::$ready;
            $wallet->flags = [];
            $wallet->terminal_type = PaymentSetupTerminalType::$wap;
            $wallet->os_type = PaymentSetupOsType::$android;

            $this->assertSame(
                ["status" => "ready", "flags" => [], "terminal_type" => "wap", "os_type" => "android"],
                $this->roundTrip($wallet),
                $class
            );
        }
    }

    public function testTamaraRoundTripKeepsEveryProperty()
    {
        $risk = new TamaraRiskAssessment();
        $risk->premium_customer = false;
        $risk->risk_score = 42;
        $risk->order_count_last24h = 0;
        $risk->linked_device_count = 2;

        $tamara = new Tamara();
        $tamara->status = PaymentMethodStatus::$ready;
        $tamara->flags = [];
        $tamara->initialization = PaymentMethodInitialization::$enabled;
        $tamara->risk_assessment = $risk;

        $this->assertSame(
            [
                "status" => "ready",
                "flags" => [],
                "initialization" => "enabled",
                "risk_assessment" => [
                    "premium_customer" => false,
                    "risk_score" => 42,
                    "order_count_last24h" => 0,
                    "linked_device_count" => 2,
                ],
            ],
            $this->roundTrip($tamara)
        );
        $this->assertInstanceOf(PaymentMethodBase::class, $tamara);
    }

    public function testP24RoundTripKeepsEveryProperty()
    {
        $holder = new P24AccountHolder();
        $holder->name = "John Smith";
        $holder->email = "john.smith@example.com";

        $p24 = new P24();
        $p24->status = PaymentMethodStatus::$ready;
        $p24->flags = [];
        $p24->account_holder = $holder;

        $this->assertSame(
            [
                "status" => "ready",
                "flags" => [],
                "account_holder" => ["name" => "John Smith", "email" => "john.smith@example.com"],
            ],
            $this->roundTrip($p24)
        );
    }

    public function testSwishRoundTripKeepsEveryProperty()
    {
        $holder = new SwishAccountHolder();
        $holder->first_name = "John";
        $holder->last_name = "Smith";

        $swish = new Swish();
        $swish->status = PaymentMethodStatus::$ready;
        $swish->flags = [];
        $swish->billing_descriptor = "ACME STORE";
        $swish->account_holder = $holder;

        $this->assertSame(
            [
                "status" => "ready",
                "flags" => [],
                "billing_descriptor" => "ACME STORE",
                "account_holder" => ["first_name" => "John", "last_name" => "Smith"],
            ],
            $this->roundTrip($swish)
        );
    }

    public function testAchRoundTripKeepsEveryProperty()
    {
        $identification = new AchAccountHolderIdentification();
        $identification->type = "ssn";
        $identification->issuing_country = "US";
        $identification->number = "123456789";

        $holder = new AchAccountHolder();
        $holder->type = AchAccountHolderType::$individual;
        $holder->first_name = "John";
        $holder->last_name = "Smith";
        $holder->company_name = "Acme Corporation";
        $holder->date_of_birth = "1985-05-15";
        $holder->identification = $identification;

        $ach = new Ach();
        $ach->status = PaymentMethodStatus::$ready;
        $ach->flags = [];
        $ach->account_type = PaymentSetupAchAccountType::$savings;
        $ach->account_holder = $holder;
        $ach->account_number = "1234567890";
        $ach->bank_code = "011000015";
        $ach->country = "US";

        $this->assertSame(
            [
                "status" => "ready",
                "flags" => [],
                "account_type" => "savings",
                "account_holder" => [
                    "type" => "individual",
                    "first_name" => "John",
                    "last_name" => "Smith",
                    "company_name" => "Acme Corporation",
                    "date_of_birth" => "1985-05-15",
                    "identification" => ["type" => "ssn", "issuing_country" => "US", "number" => "123456789"],
                ],
                "account_number" => "1234567890",
                "bank_code" => "011000015",
                "country" => "US",
            ],
            $this->roundTrip($ach)
        );
    }

    public function testSepaRoundTripKeepsEveryPropertyAndSendsTheMandateDateAsDateOnly()
    {
        $holder = new SepaAccountHolder();
        $holder->type = SepaAccountHolderType::$corporate;
        $holder->first_name = "John";
        $holder->last_name = "Smith";
        $holder->company_name = "Acme Corporation";

        $mandate = new SepaMandate();
        $mandate->id = "mandate_123";
        $mandate->type = PaymentSetupSepaMandateType::$b2b;
        $mandate->date_of_signature = new DateTime("2026-01-15T10:30:00Z");

        $sepa = new Sepa();
        $sepa->status = PaymentMethodStatus::$ready;
        $sepa->flags = [];
        $sepa->account_holder = $holder;
        $sepa->account_number = "DE89370400440532013000";
        $sepa->country = "DE";
        $sepa->currency = "EUR";
        $sepa->mandate = $mandate;

        $this->assertSame(
            [
                "status" => "ready",
                "flags" => [],
                "account_holder" => [
                    "type" => "corporate",
                    "first_name" => "John",
                    "last_name" => "Smith",
                    "company_name" => "Acme Corporation",
                ],
                "account_number" => "DE89370400440532013000",
                "country" => "DE",
                "currency" => "EUR",
                "mandate" => ["id" => "mandate_123", "type" => "b2b", "date_of_signature" => "2026-01-15"],
            ],
            $this->roundTrip($sepa)
        );
    }

    public function testNewConstantsMatchTheSpec()
    {
        $this->assertSame("en", KnetLanguage::$en);
        $this->assertSame("ar", KnetLanguage::$ar);
        $this->assertSame("web", PaymentSetupTerminalType::$web);
        $this->assertSame("wap", PaymentSetupTerminalType::$wap);
        $this->assertSame("app", PaymentSetupTerminalType::$app);
        $this->assertSame("android", PaymentSetupOsType::$android);
        $this->assertSame("ios", PaymentSetupOsType::$ios);
        $this->assertSame("savings", PaymentSetupAchAccountType::$savings);
        $this->assertSame("current", PaymentSetupAchAccountType::$current);
        $this->assertSame("cash", PaymentSetupAchAccountType::$cash);
        $this->assertSame("individual", AchAccountHolderType::$individual);
        $this->assertSame("corporate", AchAccountHolderType::$corporate);
        $this->assertSame("government", AchAccountHolderType::$government);
        $this->assertSame("individual", SepaAccountHolderType::$individual);
        $this->assertSame("corporate", SepaAccountHolderType::$corporate);
        $this->assertSame("core", PaymentSetupSepaMandateType::$core);
        $this->assertSame("b2b", PaymentSetupSepaMandateType::$b2b);

        $this->assertCount(2, get_class_vars(KnetLanguage::class));
        $this->assertCount(3, get_class_vars(PaymentSetupTerminalType::class));
        $this->assertCount(2, get_class_vars(PaymentSetupOsType::class));
        $this->assertCount(3, get_class_vars(PaymentSetupAchAccountType::class));
        $this->assertCount(3, get_class_vars(AchAccountHolderType::class));
        $this->assertCount(2, get_class_vars(SepaAccountHolderType::class));
        $this->assertCount(2, get_class_vars(PaymentSetupSepaMandateType::class));
    }

    public function testDeserializesSwaggerExampleOfTheNewPaymentMethods()
    {
        $json = '{
            "payment_methods": {
                "qpay": { "status": "ready", "flags": [], "national_id": "21234567890" },
                "ideal": { "status": "ready", "flags": [], "description": "2 t-shirts" },
                "knet": { "status": "ready", "flags": [], "language": "en" },
                "blik": { "status": "ready", "flags": [], "partner_code": "999111" },
                "gcash": { "status": "ready", "flags": [], "terminal_type": "web", "os_type": "android" },
                "tamara": {
                    "status": "ready",
                    "flags": [],
                    "initialization": "disabled",
                    "risk_assessment": {
                        "premium_customer": false,
                        "risk_score": 42,
                        "order_count_last24h": 0,
                        "linked_device_count": 2
                    }
                },
                "swish": {
                    "status": "ready",
                    "flags": [],
                    "account_holder": { "first_name": "John", "last_name": "Smith" }
                },
                "paynow": { "status": "unavailable", "flags": [] }
            }
        }';

        $paymentMethods = (new JsonSerializer())->deserialize($json)['payment_methods'];

        $this->assertSame("21234567890", $paymentMethods['qpay']['national_id']);
        $this->assertSame("2 t-shirts", $paymentMethods['ideal']['description']);
        $this->assertSame(KnetLanguage::$en, $paymentMethods['knet']['language']);
        $this->assertSame("999111", $paymentMethods['blik']['partner_code']);
        $this->assertSame(PaymentSetupTerminalType::$web, $paymentMethods['gcash']['terminal_type']);
        $this->assertSame(PaymentSetupOsType::$android, $paymentMethods['gcash']['os_type']);
        $this->assertSame(PaymentMethodInitialization::$disabled, $paymentMethods['tamara']['initialization']);
        $this->assertSame(
            ["premium_customer" => false, "risk_score" => 42, "order_count_last24h" => 0, "linked_device_count" => 2],
            $paymentMethods['tamara']['risk_assessment']
        );
        $this->assertSame(["first_name" => "John", "last_name" => "Smith"], $paymentMethods['swish']['account_holder']);
        $this->assertSame(PaymentMethodStatus::$unavailable, $paymentMethods['paynow']['status']);
    }

    public function testPaymentTypeDefaultsToLowercaseRegular()
    {
        $request = new PaymentSetupRequest();

        $this->assertSame("regular", $request->payment_type);
        $this->assertSame('{"payment_type":"regular"}', $this->encode($request));
    }

    public function testSerializesEveryPaymentSetupPaymentTypeValue()
    {
        $expected = ["regular", "recurring", "moto", "installment", "pay_later", "unscheduled"];
        $this->assertSame($expected, array_values(get_class_vars(PaymentSetupPaymentType::class)));

        foreach ($expected as $value) {
            $request = new PaymentSetupRequest();
            $request->payment_type = $value;

            $this->assertSame('{"payment_type":"' . $value . '"}', $this->encode($request));
        }
        $this->assertSame("pay_later", PaymentSetupPaymentType::$pay_later);
    }

    public function testSerializesBillingAddressUnderLiteralKeys()
    {
        $address = new Address();
        $address->address_line1 = "10 Canterbury Road";
        $address->address_line2 = "Flat 2";
        $address->city = "London";
        $address->state = "Greater London";
        $address->zip = "NE1 1KO";
        $address->country = "GB";

        $billing = new PaymentSetupBilling();
        $billing->address = $address;

        $request = new PaymentSetupRequest();
        $request->billing = $billing;

        $decoded = $this->roundTrip($request);

        $this->assertSame(
            [
                "address" => [
                    "address_line1" => "10 Canterbury Road",
                    "address_line2" => "Flat 2",
                    "city" => "London",
                    "state" => "Greater London",
                    "zip" => "NE1 1KO",
                    "country" => "GB",
                ],
            ],
            $decoded['billing']
        );
        $this->assertStringContainsString(
            '"billing":{"address":{"address_line1":"10 Canterbury Road"',
            $this->encode($request)
        );
    }

    public function testDeprecatedInitializationIsStillSentWhenSet()
    {
        $bizum = new Bizum();
        $bizum->initialization = PaymentMethodInitialization::$enabled;
        $payByBank = new PayByBank();
        $payByBank->initialization = PaymentMethodInitialization::$enabled;
        $payByBank->bank_id = "ob-natwest";
        $stablecoin = new Stablecoin();
        $stablecoin->initialization = PaymentMethodInitialization::$disabled;

        $this->assertSame(["initialization" => "enabled"], $this->roundTrip($bizum));
        $this->assertSame(["initialization" => "enabled", "bank_id" => "ob-natwest"], $this->roundTrip($payByBank));
        $this->assertSame(["initialization" => "disabled"], $this->roundTrip($stablecoin));

        foreach ([$bizum, $payByBank, $stablecoin] as $method) {
            $this->assertInstanceOf(PaymentMethodBase::class, $method);
        }
    }

    public function testInitializationIsRedeclaredAsDeprecatedOnlyWhereTheSchemaLacksIt()
    {
        foreach ([Bizum::class, PayByBank::class, Stablecoin::class] as $class) {
            $property = new \ReflectionProperty($class, "initialization");
            $this->assertSame($class, $property->getDeclaringClass()->getName(), $class);
            $this->assertStringContainsString("@deprecated", $property->getDocComment(), $class);
        }

        $base = new \ReflectionProperty(PaymentMethodBase::class, "initialization");
        $this->assertStringNotContainsString("@deprecated", $base->getDocComment());
    }

    public function testDeprecatedInitializationIsNotSentWhenUnset()
    {
        $payByBank = new PayByBank();
        $payByBank->bank_id = "ob-natwest";

        $this->assertSame('{"bank_id":"ob-natwest"}', $this->encode($payByBank));
    }

    public function testDeprecatedBizumPaymentMethodOptionsIsStillSentWhenSet()
    {
        $option = new PaymentMethodOption();
        $option->id = "pmo_123";

        $options = new PaymentMethodOptions();
        $options->sdk = $option;

        $bizum = new Bizum();
        $bizum->status = PaymentMethodStatus::$ready;
        $bizum->flags = [];
        $bizum->initialization = PaymentMethodInitialization::$disabled;
        $bizum->payment_method_options = $options;

        $this->assertSame(
            [
                "status" => "ready",
                "flags" => [],
                "initialization" => "disabled",
                "payment_method_options" => ["sdk" => ["id" => "pmo_123"]],
            ],
            $this->roundTrip($bizum)
        );
    }

    public function testDeprecatedCardPresentIsStillSentUnderItsKey()
    {
        $pin = new CardPresentPin();
        $pin->key_set_id = "ks_123";
        $pin->block = "0123456789ABCDEF";
        $pin->block_format = "iso_0";

        $cardPresent = new CardPresent();
        $cardPresent->entry_mode = "chip";
        $cardPresent->pin = $pin;

        $paymentMethods = new PaymentMethods();
        $paymentMethods->card_present = $cardPresent;

        $this->assertSame(
            '{"card_present":{"entry_mode":"chip","pin":{"key_set_id":"ks_123","block":"0123456789ABCDEF",'
            . '"block_format":"iso_0"}}}',
            $this->encode($paymentMethods)
        );
    }

    public function testEmptyMethodsTheApiRejectsAsArraySerializeAsJsonObject()
    {
        $paymentMethods = new PaymentMethods();
        $paymentMethods->alma = new Alma();
        $paymentMethods->sequra = new Sequra();
        $paymentMethods->wechatpay = new WeChatPay();

        $json = $this->encode($paymentMethods);

        $this->assertStringContainsString('"alma":{}', $json);
        $this->assertStringContainsString('"sequra":{}', $json);
        $this->assertStringContainsString('"wechatpay":{}', $json);
        $this->assertStringNotContainsString('[]', $json);
        $this->assertSame('{"alma":{}}', $this->encode(["alma" => new Alma()]));
    }

    public function testEmptyMethodTheApiAcceptsInBothFormsAlsoSerializesAsJsonObject()
    {
        $paymentMethods = new PaymentMethods();
        $paymentMethods->eps = new Eps();

        $this->assertSame('{"eps":{}}', $this->encode($paymentMethods));
        $this->assertSame('{}', $this->encode(new Eps()));
    }

    public function testExistingMethodsWithNothingSetSerializeAsJsonObject()
    {
        $paymentMethods = new PaymentMethods();
        $paymentMethods->bizum = new Bizum();
        $paymentMethods->paybybank = new PayByBank();
        $paymentMethods->stablecoin = new Stablecoin();

        $json = $this->encode($paymentMethods);

        $this->assertStringContainsString('"bizum":{}', $json);
        $this->assertStringContainsString('"paybybank":{}', $json);
        $this->assertStringContainsString('"stablecoin":{}', $json);
    }

    public function testEmptyListInsideAMethodStillSerializesAsJsonArray()
    {
        $alma = new Alma();
        $alma->flags = [];
        $paymentMethods = new PaymentMethods();
        $paymentMethods->alma = $alma;

        $this->assertSame('{"alma":{"flags":[]}}', $this->encode($paymentMethods));
    }

    public function testEmptyObjectOutsidePaymentMethodsIsUnchanged()
    {
        $billing = new PaymentSetupBilling();
        $billing->address = new Address();

        $this->assertSame('{"address":[]}', $this->encode($billing));
    }
}
