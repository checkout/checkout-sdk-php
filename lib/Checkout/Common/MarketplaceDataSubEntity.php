<?php

namespace Checkout\Common;

class MarketplaceDataSubEntity
{
    public string $id;

    public int $amount;

    public string $reference;

    public MarketplaceCommission $commission;

}
