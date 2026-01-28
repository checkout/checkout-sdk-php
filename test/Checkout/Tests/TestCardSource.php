<?php

namespace Checkout\Tests;

class TestCardSource
{
    public static $VisaName = "Mr. Test";
    public static $VisaNumber = "4242424242424242";
    public static $VisaExpiryMonth = 12;
    public static $VisaExpiryYear = ""; // Will be set to current year
    public static $VisaCvv = "100";
}

// Initialize the current year when the class is loaded
TestCardSource::$VisaExpiryYear = (int)date('Y');
