<?php

namespace Checkout;

interface SdkCredentialsInterface
{
    public function getAuthorization($authorizationType);
}
