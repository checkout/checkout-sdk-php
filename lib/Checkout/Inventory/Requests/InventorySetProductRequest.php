<?php

namespace Checkout\Inventory\Requests;

use Checkout\Inventory\Entities\InventoryMoney;

/**
 * [Beta]
 * The request body for setting the product knowledge for a variant. The call is an upsert.
 */
class InventorySetProductRequest
{
    /**
     * The product's display title.
     * [Required]
     * max 512 characters
     *
     * @var string
     */
    public $title;

    /**
     * The product's display description.
     * [Required]
     * max 4000 characters
     *
     * @var string
     */
    public $description;

    /**
     * The canonical URL for the product page.
     * [Required]
     * max 2048 characters
     *
     * @var string
     */
    public $product_url;

    /**
     * The URL of the primary product image.
     * [Required]
     * max 2048 characters
     *
     * @var string
     */
    public $image_url;

    /**
     * Additional product image URLs, beyond image_url.
     * [Optional]
     *
     * @var string[]|null
     */
    public $additional_image_urls;

    /**
     * The URL of a product video.
     * [Optional]
     *
     * @var string|null
     */
    public $video_url;

    /**
     * The URL of a 3D model of the product.
     * [Optional]
     *
     * @var string|null
     */
    public $model_3d_url;

    /**
     * The merchant's stock-keeping unit for the product.
     * [Optional]
     * max 128 characters
     *
     * @var string|null
     */
    public $sku;

    /**
     * The product's Global Trade Item Number (UPC, EAN, ISBN, or JAN).
     * [Optional]
     *
     * @var string|null
     */
    public $gtin;

    /**
     * The product's Manufacturer Part Number.
     * [Optional]
     *
     * @var string|null
     */
    public $mpn;

    /**
     * The product's brand name.
     * [Optional]
     *
     * @var string|null
     */
    public $brand;

    /**
     * The merchant's category for the product.
     * [Optional]
     *
     * @var string|null
     */
    public $category;

    /**
     * The product's regular price. When provided together with sale_price, sale_price must use
     * the same currency and be less than or equal to price.
     * [Optional]
     *
     * @var InventoryMoney|null
     */
    public $price;

    /**
     * The product's discounted price. Must share price's currency and be less than or equal
     * to price.
     * [Optional]
     *
     * @var InventoryMoney|null
     */
    public $sale_price;

    /**
     * The date and time from which sale_price applies. Paired with sale_price.
     * [Optional]
     * Format: date-time
     *
     * @var string|null
     */
    public $sale_price_starts_at;

    /**
     * The date and time after which sale_price no longer applies. Paired with sale_price.
     * [Optional]
     * Format: date-time
     *
     * @var string|null
     */
    public $sale_price_ends_at;

    /**
     * The identifier shared by all variants of the same product (for example, the same belt in
     * different sizes). When set, color and size are both required.
     * [Optional]
     *
     * @var string|null
     */
    public $group_id;

    /**
     * A display title for the variant group.
     * [Optional]
     *
     * @var string|null
     */
    public $group_title;

    /**
     * The variant's color. Required when group_id is set.
     * [Optional]
     *
     * @var string|null
     */
    public $color;

    /**
     * The variant's size. Required when group_id is set.
     * [Optional]
     *
     * @var string|null
     */
    public $size;

    /**
     * The sizing system that size is expressed in.
     * [Optional]
     *
     * @var string|null
     */
    public $size_system;

    /**
     * The target gender for the product.
     * [Optional]
     *
     * @var string|null
     */
    public $gender;

    /**
     * The product's condition, as an exact lowercase match of one of the enum values. Defaults
     * to "new" when omitted.
     * [Optional]
     * Enum: new, used, refurbished (see InventoryConditionType)
     *
     * @var string|null
     */
    public $condition;

    /**
     * The product's primary material.
     * [Optional]
     *
     * @var string|null
     */
    public $material;

    /**
     * The target age group for the product.
     * [Optional]
     *
     * @var string|null
     */
    public $age_group;

    /**
     * The product's length. length, width, height and dimension_unit must be provided together,
     * or not at all.
     * [Optional]
     *
     * @var float|null
     */
    public $length;

    /**
     * The product's width. length, width, height and dimension_unit must be provided together,
     * or not at all.
     * [Optional]
     *
     * @var float|null
     */
    public $width;

    /**
     * The product's height. length, width, height and dimension_unit must be provided together,
     * or not at all.
     * [Optional]
     *
     * @var float|null
     */
    public $height;

    /**
     * The unit that length, width and height are expressed in. Required when any of length,
     * width or height is set.
     * [Optional]
     *
     * @var string|null
     */
    public $dimension_unit;

    /**
     * The product's weight. Must be provided together with weight_unit, or not at all.
     * [Optional]
     *
     * @var float|null
     */
    public $weight;

    /**
     * The unit that weight is expressed in. Required when weight is set.
     * [Optional]
     *
     * @var string|null
     */
    public $weight_unit;

    /**
     * The date and time after which the product should no longer be offered.
     * [Optional]
     * Format: date-time
     *
     * @var string|null
     */
    public $expiration_date;

    /**
     * The product's Harmonized System (HS) code, for customs purposes.
     * [Optional]
     *
     * @var string|null
     */
    public $harmonized_system_code;

    /**
     * The two-letter ISO 3166-1 alpha-2 country of origin.
     * [Optional]
     *
     * @var string|null
     */
    public $country_of_origin;

    /**
     * The name of the seller of record, when different from the merchant.
     * [Optional]
     *
     * @var string|null
     */
    public $seller_name;

    /**
     * The URL of the seller of record.
     * [Optional]
     *
     * @var string|null
     */
    public $seller_url;

    /**
     * The URL of the seller's privacy policy.
     * [Optional]
     *
     * @var string|null
     */
    public $seller_privacy_policy;

    /**
     * The URL of the seller's terms of service.
     * [Optional]
     *
     * @var string|null
     */
    public $seller_tos;
}
