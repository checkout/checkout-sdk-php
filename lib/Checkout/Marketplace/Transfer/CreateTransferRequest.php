<?php

namespace Checkout\Marketplace\Transfer;

class CreateTransferRequest
{
    /**
     * @var string
     */
    public $reference;

    /**
     * @var TransferType
     */
    public $transfer_type;

    /**
     * @var TransferSource
     */
    public $source;

    /**
     * @var TransferDestination
     */
    public $destination;
}
