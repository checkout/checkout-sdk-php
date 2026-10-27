<?php

namespace Checkout\Tests\Payments\Setups;

use Checkout\Common\Address;
use Checkout\Common\EmptyAsObject;
use Checkout\JsonSerializer;
use Checkout\Payments\Setups\Common\BillingDescriptor\PaymentSetupBillingDescriptor;
use Checkout\Payments\Setups\Common\Billing\PaymentSetupBilling;
use Checkout\Payments\Setups\Common\Customer\Customer;
use Checkout\Payments\Setups\Common\Customer\Device;
use Checkout\Payments\Setups\Common\Customer\Email;
use Checkout\Payments\Setups\Common\Customer\MerchantAccount;
use Checkout\Payments\Setups\Common\Industry\AccommodationData;
use Checkout\Payments\Setups\Common\Industry\AccommodationHost;
use Checkout\Payments\Setups\Common\Industry\AirlineData;
use Checkout\Payments\Setups\Common\Industry\AirlineInsurance;
use Checkout\Payments\Setups\Common\Industry\AirlineInsurancePrice;
use Checkout\Payments\Setups\Common\Industry\Industry;
use Checkout\Payments\Setups\Common\Industry\PaymentSetupAccommodationAddress;
use Checkout\Payments\Setups\Common\Industry\PaymentSetupAccommodationRoom;
use Checkout\Payments\Setups\Common\Order\AmountAllocationCommission;
use Checkout\Payments\Setups\Common\Order\Order;
use Checkout\Payments\Setups\Common\Order\OrderSubMerchant;
use Checkout\Payments\Setups\Common\Order\PaymentSetupAmountAllocation;
use Checkout\Payments\Setups\Common\PaymentMethods\Ach\Ach;
use Checkout\Payments\Setups\Common\PaymentMethods\Ach\AchAccountHolder;
use Checkout\Payments\Setups\Common\PaymentMethods\Ach\AchAccountHolderIdentification;
use Checkout\Payments\Setups\Common\PaymentMethods\AlipayCn\AlipayCn;
use Checkout\Payments\Setups\Common\PaymentMethods\AlipayHk\AlipayHk;
use Checkout\Payments\Setups\Common\PaymentMethods\Alma\Alma;
use Checkout\Payments\Setups\Common\PaymentMethods\ApplePay\ApplePay;
use Checkout\Payments\Setups\Common\PaymentMethods\ApplePay\ApplePayTokenData;
use Checkout\Payments\Setups\Common\PaymentMethods\ApplePay\ApplePayTokenDataHeader;
use Checkout\Payments\Setups\Common\PaymentMethods\Bacs\Bacs;
use Checkout\Payments\Setups\Common\PaymentMethods\Bacs\BacsAccountHolder;
use Checkout\Payments\Setups\Common\PaymentMethods\Bancontact\Bancontact;
use Checkout\Payments\Setups\Common\PaymentMethods\Benefit\Benefit;
use Checkout\Payments\Setups\Common\PaymentMethods\Bizum\Bizum;
use Checkout\Payments\Setups\Common\PaymentMethods\Blik\Blik;
use Checkout\Payments\Setups\Common\PaymentMethods\CardPresent\CardPresent;
use Checkout\Payments\Setups\Common\PaymentMethods\CardPresent\CardPresentPin;
use Checkout\Payments\Setups\Common\PaymentMethods\Card\Card;
use Checkout\Payments\Setups\Common\PaymentMethods\Card\CardDetails;
use Checkout\Payments\Setups\Common\PaymentMethods\Card\CardToken;
use Checkout\Payments\Setups\Common\PaymentMethods\CashApp\CashApp;
use Checkout\Payments\Setups\Common\PaymentMethods\CashApp\CashAppAction;
use Checkout\Payments\Setups\Common\PaymentMethods\CashApp\CashAppAddress;
use Checkout\Payments\Setups\Common\PaymentMethods\CashApp\CashAppCustomerProfile;
use Checkout\Payments\Setups\Common\PaymentMethods\Common\CardAccountHolder;
use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentMethodAction;
use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentMethodOption;
use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentMethodOptions;
use Checkout\Payments\Setups\Common\PaymentMethods\Common\WalletToken;
use Checkout\Payments\Setups\Common\PaymentMethods\Dana\Dana;
use Checkout\Payments\Setups\Common\PaymentMethods\Eps\Eps;
use Checkout\Payments\Setups\Common\PaymentMethods\GCash\GCash;
use Checkout\Payments\Setups\Common\PaymentMethods\GooglePay\GooglePay;
use Checkout\Payments\Setups\Common\PaymentMethods\GooglePay\GooglePayTokenData;
use Checkout\Payments\Setups\Common\PaymentMethods\Ideal\Ideal;
use Checkout\Payments\Setups\Common\PaymentMethods\Instrument\PaymentSetupInstrument;
use Checkout\Payments\Setups\Common\PaymentMethods\Instrument\PaymentSetupInstrumentAction;
use Checkout\Payments\Setups\Common\PaymentMethods\KakaoPay\KakaoPay;
use Checkout\Payments\Setups\Common\PaymentMethods\Klarna\Klarna;
use Checkout\Payments\Setups\Common\PaymentMethods\Klarna\KlarnaAccountHolder;
use Checkout\Payments\Setups\Common\PaymentMethods\Klarna\KlarnaAction;
use Checkout\Payments\Setups\Common\PaymentMethods\Knet\Knet;
use Checkout\Payments\Setups\Common\PaymentMethods\MbWay\MbWay;
use Checkout\Payments\Setups\Common\PaymentMethods\MobilePay\MobilePay;
use Checkout\Payments\Setups\Common\PaymentMethods\Multibanco\Multibanco;
use Checkout\Payments\Setups\Common\PaymentMethods\Octopus\Octopus;
use Checkout\Payments\Setups\Common\PaymentMethods\P24\P24;
use Checkout\Payments\Setups\Common\PaymentMethods\P24\P24AccountHolder;
use Checkout\Payments\Setups\Common\PaymentMethods\PayByBank\PayByBank;
use Checkout\Payments\Setups\Common\PaymentMethods\PayByBank\PayByBankAction;
use Checkout\Payments\Setups\Common\PaymentMethods\PayByBank\PayByBankBank;
use Checkout\Payments\Setups\Common\PaymentMethods\PayNow\PayNow;
use Checkout\Payments\Setups\Common\PaymentMethods\PaymentMethods;
use Checkout\Payments\Setups\Common\PaymentMethods\Paypal\Paypal;
use Checkout\Payments\Setups\Common\PaymentMethods\Paypal\PaypalAction;
use Checkout\Payments\Setups\Common\PaymentMethods\Qpay\Qpay;
use Checkout\Payments\Setups\Common\PaymentMethods\Sepa\Sepa;
use Checkout\Payments\Setups\Common\PaymentMethods\Sepa\SepaAccountHolder;
use Checkout\Payments\Setups\Common\PaymentMethods\Sepa\SepaMandate;
use Checkout\Payments\Setups\Common\PaymentMethods\Sequra\Sequra;
use Checkout\Payments\Setups\Common\PaymentMethods\Stablecoin\Stablecoin;
use Checkout\Payments\Setups\Common\PaymentMethods\Stcpay\Stcpay;
use Checkout\Payments\Setups\Common\PaymentMethods\Stcpay\StcpayAction;
use Checkout\Payments\Setups\Common\PaymentMethods\Swish\Swish;
use Checkout\Payments\Setups\Common\PaymentMethods\Swish\SwishAccountHolder;
use Checkout\Payments\Setups\Common\PaymentMethods\Tabby\Tabby;
use Checkout\Payments\Setups\Common\PaymentMethods\Tamara\Tamara;
use Checkout\Payments\Setups\Common\PaymentMethods\Tamara\TamaraRiskAssessment;
use Checkout\Payments\Setups\Common\PaymentMethods\Tng\Tng;
use Checkout\Payments\Setups\Common\PaymentMethods\TrueMoney\TrueMoney;
use Checkout\Payments\Setups\Common\PaymentMethods\Twint\Twint;
use Checkout\Payments\Setups\Common\PaymentMethods\Vipps\Vipps;
use Checkout\Payments\Setups\Common\PaymentMethods\WeChatPay\WeChatPay;
use Checkout\Payments\Setups\Common\PresentmentDetails\PaymentSetupPresentmentDetails;
use Checkout\Payments\Setups\Common\Risk\PaymentSetupRisk;
use Checkout\Payments\Setups\Common\Settings\Settings;
use Checkout\Payments\Setups\Common\Terminal\PaymentSetupTerminal;
use Checkout\Payments\Setups\Request\PaymentSetupRequest;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * The API rejects an empty object sent as [] with 422 <path>_property_invalid and accepts {}. Every Payment
 * Setup class that maps a JSON object is annotated #[EmptyAsObject], so with nothing set it is sent as {}.
 */
class PaymentSetupEmptyObjectsSerializationTest extends TestCase
{
    private function encode($value): string
    {
        return (new JsonSerializer())->serialize($value);
    }

    public function emptyObjectClasses(): array
    {
        return [
            [PaymentSetupBilling::class],
            [PaymentSetupBillingDescriptor::class],
            [Customer::class],
            [Device::class],
            [Email::class],
            [MerchantAccount::class],
            [AccommodationData::class],
            [AccommodationHost::class],
            [AirlineData::class],
            [AirlineInsurance::class],
            [AirlineInsurancePrice::class],
            [Industry::class],
            [PaymentSetupAccommodationAddress::class],
            [PaymentSetupAccommodationRoom::class],
            [AmountAllocationCommission::class],
            [Order::class],
            [OrderSubMerchant::class],
            [PaymentSetupAmountAllocation::class],
            [Ach::class],
            [AchAccountHolder::class],
            [AchAccountHolderIdentification::class],
            [AlipayCn::class],
            [AlipayHk::class],
            [Alma::class],
            [ApplePay::class],
            [ApplePayTokenData::class],
            [ApplePayTokenDataHeader::class],
            [Bacs::class],
            [BacsAccountHolder::class],
            [Bancontact::class],
            [Benefit::class],
            [Bizum::class],
            [Blik::class],
            [Card::class],
            [CardDetails::class],
            [CardToken::class],
            [CardPresent::class],
            [CardPresentPin::class],
            [CashApp::class],
            [CashAppAction::class],
            [CashAppAddress::class],
            [CashAppCustomerProfile::class],
            [CardAccountHolder::class],
            [PaymentMethodAction::class],
            [PaymentMethodOption::class],
            [PaymentMethodOptions::class],
            [WalletToken::class],
            [Dana::class],
            [Eps::class],
            [GCash::class],
            [GooglePay::class],
            [GooglePayTokenData::class],
            [Ideal::class],
            [PaymentSetupInstrument::class],
            [PaymentSetupInstrumentAction::class],
            [KakaoPay::class],
            [Klarna::class],
            [KlarnaAccountHolder::class],
            [KlarnaAction::class],
            [Knet::class],
            [MbWay::class],
            [MobilePay::class],
            [Multibanco::class],
            [Octopus::class],
            [P24::class],
            [P24AccountHolder::class],
            [PayByBank::class],
            [PayByBankAction::class],
            [PayByBankBank::class],
            [PayNow::class],
            [PaymentMethods::class],
            [Paypal::class],
            [PaypalAction::class],
            [Qpay::class],
            [Sepa::class],
            [SepaAccountHolder::class],
            [SepaMandate::class],
            [Sequra::class],
            [Stablecoin::class],
            [Stcpay::class],
            [StcpayAction::class],
            [Swish::class],
            [SwishAccountHolder::class],
            [Tabby::class],
            [Tamara::class],
            [TamaraRiskAssessment::class],
            [Tng::class],
            [TrueMoney::class],
            [Twint::class],
            [Vipps::class],
            [WeChatPay::class],
            [PaymentSetupPresentmentDetails::class],
            [PaymentSetupRisk::class],
            [Settings::class],
            [PaymentSetupTerminal::class],
            [PaymentSetupRequest::class],
        ];
    }

    /**
     * @dataProvider emptyObjectClasses
     */
    public function testEmptyObjectSerializesAsJsonObject(string $class)
    {
        $object = new $class();
        if ($object instanceof PaymentSetupRequest) {
            $object->payment_type = null;
        }

        $this->assertSame('{}', $this->encode($object), $class);
        $this->assertSame('{"value":{}}', $this->encode(["value" => $object]), $class);
    }

    public function testEveryPaymentSetupObjectClassIsAnnotated()
    {
        $root = __DIR__ . "/../../../../../lib/Checkout/Payments/Setups";
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($root));
        $checked = 0;
        foreach ($files as $file) {
            if ($file->getExtension() !== "php") {
                continue;
            }
            $source = file_get_contents($file->getPathname());
            if (!preg_match('/^namespace ([^;]+);/m', $source, $namespace)
                || !preg_match('/^(?:abstract )?class (\\w+)/m', $source, $name)) {
                continue;
            }
            $reflected = new ReflectionClass($namespace[1] . "\\" . $name[1]);
            $publicInstanceProperties = array_filter(
                $reflected->getProperties(\ReflectionProperty::IS_PUBLIC),
                function ($property) {
                    return !$property->isStatic();
                }
            );
            if (count($publicInstanceProperties) === 0) {
                continue;
            }
            $annotated = false;
            for ($class = $reflected; $class; $class = $class->getParentClass()) {
                $annotated = $annotated || count($class->getAttributes(EmptyAsObject::class)) > 0;
            }
            $this->assertTrue($annotated, $reflected->getName());
            $checked++;
        }
        // The two abstract bases plus every concrete class of the data provider.
        $this->assertSame(count($this->emptyObjectClasses()) + 2, $checked);
    }

    public function testEmptyTopLevelObjectsOfTheRequestSerializeAsJsonObjects()
    {
        $request = new PaymentSetupRequest();
        $request->billing = new PaymentSetupBilling();
        $request->customer = new Customer();
        $request->order = new Order();
        $request->settings = new Settings();
        $request->payment_methods = new PaymentMethods();

        $this->assertSame(
            '{"payment_type":"regular","payment_methods":{},"settings":{},"customer":{},"order":{},"billing":{}}',
            $this->encode($request)
        );
    }

    public function testEmptyNestedObjectsOfPaymentMethodsSerializeAsJsonObjects()
    {
        $sepa = new Sepa();
        $sepa->account_holder = new SepaAccountHolder();
        $sepa->mandate = new SepaMandate();
        $ach = new Ach();
        $ach->account_holder = new AchAccountHolder();
        $swish = new Swish();
        $swish->account_holder = new SwishAccountHolder();
        $p24 = new P24();
        $p24->account_holder = new P24AccountHolder();
        $tamara = new Tamara();
        $tamara->risk_assessment = new TamaraRiskAssessment();

        $this->assertSame('{"account_holder":{},"mandate":{}}', $this->encode($sepa));
        $this->assertSame('{"account_holder":{}}', $this->encode($ach));
        $this->assertSame('{"account_holder":{}}', $this->encode($swish));
        $this->assertSame('{"account_holder":{}}', $this->encode($p24));
        $this->assertSame('{"risk_assessment":{}}', $this->encode($tamara));
    }

    public function testEmptySharedObjectInsideAPaymentSetupIsUnchanged()
    {
        $billing = new PaymentSetupBilling();
        $billing->address = new Address();

        $this->assertSame('{"address":[]}', $this->encode($billing));
    }
}
