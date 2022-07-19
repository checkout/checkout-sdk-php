<?php

namespace Checkout\Transfers;

class CreateTransferRequest
{
    /**
     * @var string
     */
    public $reference;

    /**
     * @var string value of TransferType
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
