# Checkout.com PHP SDK

[![build-status](https://github.com/checkout/checkout-sdk-php/workflows/build-master/badge.svg)](https://github.com/checkout/checkout-sdk-php/actions/workflows/build-master.yml)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=checkout_checkout-sdk-php&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=checkout_checkout-sdk-php)

[![build-status](https://github.com/checkout/checkout-sdk-php/workflows/build-release/badge.svg)](https://github.com/checkout/checkout-sdk-php/actions/workflows/build-release.yml)
[![GitHub release](https://img.shields.io/github/release/checkout/checkout-sdk-php.svg)](https://GitHub.com/checkout/checkout-sdk-php/releases/)
[![Latest Stable Version](http://poser.pugx.org/checkout/checkout-sdk-php/v)](https://packagist.org/packages/checkout/checkout-sdk-php)

[![GitHub license](https://img.shields.io/github/license/checkout/checkout-sdk-php.svg)](https://github.com/checkout/checkout-sdk-php/blob/master/LICENSE.md)


## Getting started

> **Version 3.0.0 is here!**
>  <br/><br/>
> We improved the initialization of SDK making it easier to understand the available options. <br/>
> Now `NAS` accounts are the default instance for the SDK and `ABC` structure was moved to a `previous` prefixes. <br/>
> If you have been using this SDK before, you may find the following important changes:
> * Marketplace module was moved to Accounts module, same for classes and references.
> * In most cases, IDE can help you determine from where to import, but if you’re still having issues don't hesitate to open a [ticket](https://github.com/checkout/checkout-sdk-php/issues/new/choose).


### :rocket: Please check in [GitHub releases](https://github.com/checkout/checkout-sdk-php/releases) for all the versions available.

### :book: Checkout our official documentation.

* [Official Docs (Default)](https://docs.checkout.com/)
* [Official Docs (Previous)](https://docs.checkout.com/previous)

### :books: Check out our official API documentation guide, where you can also find more usage examples.

* [API Reference (Default)](https://api-reference.checkout.com/)
* [API Reference (Previous)](https://api-reference.checkout.com/previous)

#### Composer

```json
{
  "require": {
    "php": ">=5.6",
    "checkout/checkout-sdk-php": "version"
  }
}
```

## How to use the SDK

This SDK can be used with two different pair of API keys provided by Checkout. However, using different API keys imply using specific API features. Please find in the table below the types of keys that can be used within this SDK.

| Account System | Public Key (example)                    | Secret Key (example)                    |
|----------------|-----------------------------------------| --------------------------------------- |
| Default        | pk_pkhpdtvabcf7hdgpwnbhw7r2uic          | sk_m73dzypy7cf3gf5d2xr4k7sxo4e          |
| Previous       | pk_g650ff27-7c42-4ce1-ae90-5691a188ee7b | sk_gk3517a8-3z01-45fq-b4bd-4282384b0a64 |

Note: sandbox keys have a `sbox_` or `test_` identifier, for Default and Previous accounts respectively.

**PLEASE NEVER SHARE OR PUBLISH YOUR CHECKOUT CREDENTIALS.**

If you don't have your own API keys, you can sign up for a test account [here](https://www.checkout.com/get-test-account).


### Default

Default keys client instantiation can be done as follows:

```php
$checkoutApi = CheckoutSdk::builder()->staticKeys()
                    ->publicKey("public_key") // optional, only required for operations related with tokens
                    ->secretKey("secret_key")
                    ->environment(Environment::sandbox()) // or production()
                    ->logger($logger) //optional, for a custom Logger
                    ->httpClientBuilder($client) // optional, for a custom HTTP client
                    ->build();

$paymentsClient = $checkoutApi->getPaymentsClient();
$paymentsClient->refundPayment("payment_id");
```

### Default OAuth

The SDK supports client credentials OAuth, when initialized as follows:

```php
$checkoutApi = CheckoutSdk::builder()->oAuth()
                    ->clientCredentials("client_id", "client_secret")
                    ->scopes([OAuthScope::$Gateway, OAuthScope::$Vault]) // array of scopes
                    ->environment(Environment::sandbox()) // or production()
                    ->logger($logger) //optional, for a custom Logger
                    ->httpClientBuilder($client) // optional, for a custom HTTP client
                    ->build();

$paymentsClient = $checkoutApi->getPaymentsClient();
$paymentsClient->refundPayment("payment_id");
```


### Previous

If your pair of keys matches the Previous type, this is how the SDK should be used:

```php
$checkoutApi = CheckoutSdk::builder()
                    ->previous()
                    ->staticKeys()
                    ->environment(Environment::sandbox()) // or production()
                    ->publicKey("public_key") // optional, only required for operations related with tokens
                    ->secretKey("secret_key")
                    ->logger($logger) //optional, for a custom Logger
                    ->httpClientBuilder($client) // optional, for a custom HTTP client
                    ->build();

$paymentsClient = $checkoutApi->getPaymentsClient();
$paymentsClient->refundPayment("payment_id");
```

### PHP Settings

For operations that require file upload (Disputes or Marketplace) the configuration `extension=fileinfo` must be enabled in the `php.ini`.

## Exception handling

All the API responses that do not fall in the 2** status codes, the SDK will throw a `CheckoutApiException`.

The exception encapsulates `http_metadata` and `$error_details`, if available.

## Building from source

Once you check out the code from GitHub, the project can be built using composer:

```
composer update
```

The execution of integration tests require the following environment variables set in your system:

* For default account systems (NAS): `CHECKOUT_DEFAULT_PUBLIC_KEY` & `CHECKOUT_DEFAULT_SECRET_KEY`
* For default account systems (OAuth): `CHECKOUT_DEFAULT_OAUTH_CLIENT_ID` & `CHECKOUT_DEFAULT_OAUTH_CLIENT_SECRET`
* For Previous account systems (ABC): `CHECKOUT_PREVIOUS_PUBLIC_KEY` & `CHECKOUT_PREVIOUS_SECRET_KEY`

## Code of Conduct

Please refer to [Code of Conduct](CODE_OF_CONDUCT.md)

## Licensing

[MIT](LICENSE.md)
