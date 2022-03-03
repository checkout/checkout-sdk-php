<?php

namespace Checkout\Marketplace;

use Checkout\Files\FileRequest;

class MarketplaceFileRequest extends FileRequest
{
    public string $content_type;
}
