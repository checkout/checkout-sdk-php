<?php

namespace Checkout\Accounts;

/**
 * Email addresses for this sub-entity, sent as contact_details.email_addresses.
 */
class EntityEmailAddresses
{
    /**
     * The main email address for this sub-entity.
     * [Required]
     * Format: email
     *
     * @var string
     */
    public $primary;

    /**
     * The email address of the person responsible for PCI compliance at this sub-entity.
     * [Required] for the US ISV Seller Company (3.0) and US ISV Seller Sole Trader (3.0) variants,
     * together with primary; not part of the other variants.
     * Format: email
     *
     * @var string
     */
    public $pci_compliance_contact;
}
