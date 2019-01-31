<?php

namespace Checkout\tests\Helpers;

use Checkout\Models\Sources\BillingAddress;
use Checkout\Models\Sources\Sepa;
use Checkout\Models\Sources\SepaData;

class Sources
{
    public static function generateSepaModel()
    {
        $source = new Sepa(static::generateBillingAddress(), static::generateSepaData());
        $source->_links['sepa:mandate-get']['href'] = HttpHandlers::generateURL();
        $source->_links['sepa:mandate-cancel']['href'] = HttpHandlers::generateURL();
        return $source;
    }

    public static function generateSepaData()
    {
        $data = new SepaData('$first', '$last', '$iban', '$bic', '$descriptor', '$mandate');
        return $data;
    }

    public static function generateBillingAddress()
    {
        $data = new BillingAddress('$address1', '$address2', '$city', '$state', '$zip', '$country');
        return $data;
    }

    public static function generateCustomerID()
    {
        return 'cus_' . substr(md5(rand()), 0, 26);
    }

    public static function generateSourceID()
    {
        return 'src_' . substr(md5(rand()), 0, 26);
    }

    public static function generateCustomerEmail()
    {
        return substr(md5(rand()), 0, 26) . '@checkout.com';
    }
}
