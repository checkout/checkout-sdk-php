<?php

namespace Checkout\Marketplace;

use Checkout\Files\FileRequest;

class MarketplaceFileRequest extends FileRequest
{
    /**
     * @var string
     */
    public $content_type;
}
