<?php

namespace Checkout\Common;

use Attribute;

/**
 * Marks a property that the swagger declares as `format: date` (yyyy-MM-dd) rather than
 * `format: date-time` (RFC 3339).
 *
 * JsonSerializer serializes a DateTime on an annotated property without its time component. The
 * API rejects a full date-time on those fields with 400 request_body_malformed, so the annotation
 * is load-bearing, not decorative.
 *
 * Usage:
 * <code>
 * /**
 *  * The date the customer registered.
 *  * [Optional]
 *  * Format: yyyy-MM-dd
 *  * &#64;var DateTime
 *  *&#47;
 * #[DateOnly]
 * public $registration_date;
 * </code>
 *
 * Note: the annotation is read by reflection on the owning object, so it applies to object
 * properties (including objects nested inside arrays). A DateTime placed directly as a raw array
 * value has no owning class and is formatted as a date-time.
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class DateOnly
{
}
