<?php

namespace Checkout\Instruments\Create;

/**
 * The account configuration for a Bacs Direct Debit instrument being stored.
 */
class CreateBacsInstrumentAccount
{
    /**
     * The ID of the processing channel to associate with the instrument.
     * [Required]
     * Pattern: ^(pc)_(\w{26})$
     * @var string
     */
    public $processing_channel_id;
}
