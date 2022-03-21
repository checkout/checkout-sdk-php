<?php

namespace Checkout\Marketplace\Transfer;

class CreateTransferRequest
{
    public $reference;

    // TransferType
    public $transfer_type;

    // TransferSource
    public $source;

    // TransferDestination
    public $destination;

}
