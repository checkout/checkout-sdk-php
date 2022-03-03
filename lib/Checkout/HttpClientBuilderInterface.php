<?php

namespace Checkout;

use GuzzleHttp\ClientInterface;

interface HttpClientBuilderInterface
{
    public function getClient(): ClientInterface;

}
