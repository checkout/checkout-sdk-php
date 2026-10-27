<?php

namespace Checkout\Common;

use Attribute;

/**
 * Marks a class whose instances must serialize as a JSON object even when no property is set.
 *
 * JsonSerializer turns an object into a PHP array, and json_encode writes an empty PHP array as []. For
 * a class annotated with this attribute (or extending one that is), JsonSerializer writes {} instead.
 * The API rejects [] where the schema expects an object for some fields, for example a Payment Setup
 * payment method sent with nothing set returns 422 payment_methods.<method>_property_invalid, so the
 * annotation is load-bearing, not decorative. Empty lists are not affected and stay [].
 *
 * Usage:
 * <code>
 * #[EmptyAsObject]
 * abstract class PaymentSetupPaymentMethod
 * </code>
 *
 * Note: the attribute is looked up on the class and on every parent class, so annotating a base class
 * covers all its subclasses.
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class EmptyAsObject
{
}
