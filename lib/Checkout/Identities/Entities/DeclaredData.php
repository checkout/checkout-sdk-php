<?php

namespace Checkout\Identities\Entities;

class DeclaredData
{
    /**
     * The applicant's name.
     * [Required]
     * min 2 characters, max 255 characters
     * Example: Hannah Bret
     * @var string
     */
    public $name;

    /**
     * The applicant's birth date.
     * [Optional]
     * Format: YYYY-MM-DD
     * Example: 1994-10-15
     * @var string|null
     */
    public $birth_date;
}
