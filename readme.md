# PulseFlow Consumer SDK

This is an unofficial SDK for the PulseFlow v1 API. It provides a convenient wrapper for interacting with the PulseFlow API.

## Installation

To install the SDK, use Composer:

```sh
composer require genioforge/pulseflow_consumer dev-main
```

## Configuration

Add the following configuration to your Laravel application's `config/services.php` file:

```php
return [
    'pulse' => [
        'key' => env('PULSE_API_KEY'),
        'domain' => env('PULSE_VENDOR_DOMAIN'),
        'pin' => env('PULSE_API_PIN'),
        'secret_key' => env('PULSE_SECRET_KEY'),
    ],
];
```

## Usage

### Service Provider

Register the service provider in your `config/app.php` file:

```php
'providers' => [
    // Other Service Providers

    GenioForge\Consumer\ConsumerServiceProvider::class,
],
```

### Facade

Add the facade to your `config/app.php` file:

```php
'aliases' => [
    // Other Facades

    'Consumer' => GenioForge\Consumer\ConsumerFacade::class,
],
```

## Example

Here is an example of how to use the SDK:

```php
use GenioForge\Consumer\Repository\RepositoryProvider;

$consumer = RepositoryProvider::getProvider();

// Ensure the provider is initialized
$consumer->ensure_initialised();


// Buy airtime
$reference = 'unique_reference';
$planId = 1;
$networkId = 1;
$phoneNumber = '08012345678';
$amount = 1000;
$airtimePurchaseResponse = $consumer->buy_airtime($reference, $planId, $networkId, $phoneNumber, $amount);
echo $airtimePurchaseResponse->message;
```

## Testing

To run the tests, use PHPUnit:

```sh
phpunit
```

## License

This SDK is licensed under the MIT License. See the LICENSE file for more information.