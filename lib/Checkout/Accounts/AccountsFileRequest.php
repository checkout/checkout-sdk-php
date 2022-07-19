<?php

namespace Checkout\Accounts;

use Checkout\Files\FileRequest;

class AccountsFileRequest extends FileRequest
{
    /**
     * @var string
     */
    public $content_type;
}
