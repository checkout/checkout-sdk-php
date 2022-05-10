# Checkout.com PHP SDK

![build-master](https://github.com/checkout/checkout-sdk-php/workflows/build-master/badge.svg)
[![GitHub license](https://img.shields.io/github/license/checkout/checkout-sdk-php.svg)](https://github.com/checkout/checkout-sdk-php/blob/master/LICENSE.md)
[![GitHub release](https://img.shields.io/github/release/checkout/checkout-sdk-php.svg)](https://GitHub.com/checkout/checkout-sdk-php/releases/)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=checkout_checkout-sdk-php&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=checkout_checkout-sdk-php)

## Getting started

Packages and sources are available from [Packagist](https://packagist.org/packages/checkout/checkout-sdk-php).

#### Composer

```json
{
  "require": {
    "php": ">=5.6",
    "checkout/checkout-sdk-php": "version"
  }
}
```

Please check in [GitHub releases](https://github.com/checkout/checkout-sdk-php/releases) for all the versions available.

## How to use the SDK

This SDK can be used with two different pair of API keys provided by Checkout. However, using different API keys imply using specific API features. Please find in the table below the types of keys that can be used within this SDK.

| Account System | Public Key (example)                    | Secret Key (example)                    |
|----------------|-----------------------------------------|-----------------------------------------|
| default        | pk_g650ff27-7c42-4ce1-ae90-5691a188ee7b | sk_gk3517a8-3z01-45fq-b4bd-4282384b0a64 |
| Four           | pk_pkhpdtvabcf7hdgpwnbhw7r2uic          | sk_m73dzypy7cf3gf5d2xr4k7sxo4e          |

Note: sandbox keys have a `test_` or `sbox_` identifier, for Default and Four accounts respectively.

**PLEASE NEVER SHARE OR PUBLISH YOUR CHECKOUT CREDENTIALS.**

If you don't have your own API keys, you can sign up for a test account [here](https://www.checkout.com/get-test-account).

## Default

Default keys client instantiation can be done as follows:

```php
$builder = CheckoutDefaultSdk::staticKeys();
$builder->setPublicKey("public_key"); // optional, only required for operations related with tokens
$builder->setSecretKey("secret_key");
$builder->setEnvironment(Environment::sandbox()); // or production()
$builder->setHttpClientBuilder(); // optional, for a custom HTTP client
$defaultApi = $builder->build();

$paymentsClient = $defaultApi->getPaymentsClient();
$paymentsClient->refundPayment("payment_id");
```

### Four

If your pair of keys matches the Four type, this is how the SDK should be used:

```php
$builder = CheckoutFourSdk::staticKeys();
$builder->setPublicKey("public_key"); // optional, only required for operations related with tokens
$builder->setSecretKey("secret_key");
$builder->setEnvironment(Environment::sandbox()); // or production()
$builder->setHttpClientBuilder(); // optional, for a custom HTTP client
$fourApi = $builder->build();

$paymentsClient = $fourApi->getPaymentsClient();
$paymentsClient->refundPayment("payment_id");
```

The SDK supports client credentials OAuth, when initialized as follows:

```php
$builder = CheckoutFourSdk::oAuth();
$builder->clientCredentials("client_id", "client_secret");
$builder->scopes([FourOAuthScope::$Files, FourOAuthScope::$Flow]); // array of scopes
$builder->setEnvironment(Environment::sandbox()); // or production()
$builder->setHttpClientBuilder(); // optional, for a custom HTTP client
$fourApi = $builder->build();

$paymentsClient = $fourApi->getPaymentsClient();
$paymentsClient->refundPayment("payment_id");
```

### PHP Settings

For operations that require file upload (Disputes or Marketplace) the configuration `extension=fileinfo` must be enabled in the `php.ini`.

## Exception handling

All the API responses that do not fall in the 2** status codes, the SDK will throw a `CheckoutApiException`.

The exception encapsulates `http_metadata` and `$error_details`, if available.

More documentation related to Checkout API and the SDK is available at:

* [Official Docs (Default)](https://docs.checkout.com/)
* [Official Docs (Four)](https://docs.checkout.com/four)
* [API Reference (Default)](https://api-reference.checkout.com/)
* [API Reference (Four)](https://api-reference.checkout.com/preview/crusoe/)

## Building from source

Once you checkout the code from GitHub, the project can be built using composer:

```
composer update
```

The execution of integration tests require the following environment variables set in your system:

* For Default account systems: `CHECKOUT_PUBLIC_KEY` & `CHECKOUT_SECRET_KEY`
* For Four account systems: `CHECKOUT_FOUR_PUBLIC_KEY` & `CHECKOUT_FOUR_SECRET_KEY`
* For Four account systems (OAuth): `CHECKOUT_FOUR_OAUTH_CLIENT_ID` & `CHECKOUT_FOUR_OAUTH_CLIENT_SECRET`

## Code of Conduct

Please refer to [Code of Conduct](CODE_OF_CONDUCT.md)

## Licensing

[MIT](LICENSE.md)
