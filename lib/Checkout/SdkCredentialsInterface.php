<?php

namespace Checkout;

interface SdkCredentialsInterface
{
    function getAuthorization($authorizationType);
}
