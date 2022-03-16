<?php

namespace Checkout\Marketplace\Transfer;

class CreateTransferRequest
{
    public string $reference;

    //One of TransferType
    public string $transfer_type;

    public TransferSource $source;

    public TransferDestination $destination;

}
