<?php

/**
 * Checkout.com
 * Authorised and regulated as an electronic money institution
 * by the UK Financial Conduct Authority (FCA) under number 900816.
 *
 * PHP version 7
 *
 * @category  SDK
 * @package   Checkout.com
 * @author    Platforms Development Team <platforms@checkout.com>
 * @copyright 2010-2019 Checkout.com
 * @license   https://opensource.org/licenses/mit-license.html MIT License
 * @link      https://docs.checkout.com/
 */

namespace Checkout\Models\Payments;

/**
 * Payment method Boleto.
 *
 * @category SDK
 * @package  Checkout.com
 * @author   Platforms Development Team <platforms@checkout.com>
 * @license  https://opensource.org/licenses/mit-license.html MIT License
 * @link     https://docs.checkout.com/
 */
class BoletoSource extends Source
{

    /**
     * Qualified name of the class.
     *
     * @var string
     */
    const QUALIFIED_NAME = __CLASS__;

    /**
     * Name of the model.
     *
     * @var string
     */
    const MODEL_NAME = 'boleto';


    /**
     * Magic Methods
     */

    /**
     * Initialise Boleto source.
     *
     * @param string $name      The customer's name.
     * @param string $birthdate The date of birth (YYYY-MM-DD).
     * @param string $cpf       The Brazilian personal tax identifier (Cadastro de Pessoas Físicas).
     */
    public function __construct($name, $birthdate, $cpf)
    {
        $this->type = static::MODEL_NAME;
        $this->customerName = $name;
        $this->birthDate = $birthdate;
        $this->cpf = $cpf;
    }
}
