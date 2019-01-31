The Checkout.com SDK for PHP makes it easy for developers to consume Checkout.com APIs from their PHP web servers.

We have tried to make the SDK as intuitive as possible, staying as close to our [API specification](https://api-reference.checkout.com/) as possible.

## Installation
Run `composer require checkout/checkout-sdk-php` in the root directory of your project and include the autoloader with `require_once "checkout-sdk-php/checkout.php";`.


## Configuration
Default configurations for logging, aliases, http and channel information can be edited in `checkout-sdk-php/src/config.ini`. The settings will be present across all _CheckoutApi_ instances. Only the Channel class can be overridden during runtime.

See the 'Configuration' section for more details on how to configure the SDK.

# Quick Start
To start making API requests you need to create a new instance of `CheckoutApi`:

```php
$checkout = new Checkout\CheckoutApi();
```

The constructor accepts two parameters:
1. string $secretKey: override the 'secret_key' parameter defined in the configuration file
2. bool $sandbox: override the 'environment' parameter defined in the configuration file

CheckoutApi provides access to each of our API resources, for example:

```php
$tokenResponse = $checkout->tokens()->request(...);
$paymentResponse = $checkout->payments()->request(...);
```

⚠️ Not setting neither a secret key nor a public key key will cause some operations be denied by the _gateway_.

# General Usage
To make the SDK as intuitive as possible, each API operation will return a model which has utility functionalities. For example:

```php
$refundRes = $checkout->payments()->refund($paymentID, 100);
if($refundRes->isSuccessful()) {
    $actionID = $refundRes->getActionId(); // Or access the property directly with $refundRes->action_id
}
```

ℹ️ Tip: Convert a model into array with `$model->getValues()`;

Some operations require a model as a parameter. For example:
```php
$address = new Checkout\Models\Address();
$address->country = 'FR';
$sepa = new Checkout\Models\Source('sepa', $address);
$sourceRes = $checkout->sources()->add($sepa);
```

All parameters required by our API must be passed in the model constructor, optional parameters should be set directly.


## Error Handling
There are two possible types of exceptions thrown by the SDK, `CheckoutHttpException` and `CheckoutModelException`, both are instances of `CheckoutException`.

`CheckoutHttpException`: triggered every time a problem related to the HTTP handler happens. Validation Errors (HTTP 422), Resource Not Found (HTTP 404), cURL module not present, etc...

`CheckoutModelException`: Triggered when a required field is missing in the command. The error code is always 0.

```php
try{
    $voidRes = $checkout->payments()->void('pay_invalid_id');
} catch(Checkout\Library\Exceptions\CheckoutHttpException $ex) {
    print('ERROR CODE: ' . $ex->getCode());
    print('ERROR LIST: ' . $ex->getErrors());
}
```