<?php

namespace Checkout;

interface SdkCredentialsInterface
{
    function getAuthorization(string $authorizationType): SdkAuthorization;
}
