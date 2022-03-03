<?php

namespace Checkout\Sessions;

use Checkout\Sessions\Channel\ChannelData;
use Checkout\Sessions\Completion\CompletionInfo;
use Checkout\Sessions\Source\SessionSource;

class SessionRequest
{
    public SessionSource $source;

    public int $amount;

    public string $currency;

    public string $processing_channel_id;

    public SessionMarketplaceData $marketplace;

    public string $authentication_type;

    public string $authentication_category;

    public string $challenge_indicator;

    public SessionsBillingDescriptor $billing_descriptor;

    public string $reference;

    public string $transaction_type;

    public SessionAddress $shipping_address;

    public CompletionInfo $completion;

    public ChannelData $channel_data;

}
