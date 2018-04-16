# United Domains PHP Client Library

[![Build Status](https://travis-ci.org/HadesArchitect/UnitedDomains.svg?branch=master)](https://travis-ci.org/HadesArchitect/UnitedDomains) 

A convenient tool to use United Domains API. It provides tools for direct API calls and convenient wrappers.

## Installation

```
composer require hades-architect/united-domains 0.1.0
```

## Usage

### Simple Client 

Recommended way when you would like to have it simple. Provides more convenient way to use API but doesn't cover all known API methods.

```php
$client = new \HadesArchitect\UnitedDomains\ClientFacade($username, $password);
$records = $client->getRecords('my-domain.com');
if ($client->isDomainFree('example.com')) { ... }
```

### Powerful Client

Doesn't have fancy facade methods giving you instead only the main `call` method. The way to use API directly. No limits, but great power brings great responsibility ;)

```php
$client = new \HadesArchitect\UnitedDomains\Client($username, $password);
$response = $client->call('CheckDomain', ['domain' => 'example.com']);
echo $response;
```

### Traceable Client 

It brings more output if you debug something.

```php
// Client
$client = new \HadesArchitect\UnitedDomains\TraceableClient($username, $password);
// Logger 
$client->setLogger(
    new \Monolog\Logger(
        'ud_api',
        [new \Monolog\Handler\StreamHandler('php://stdout', \Monolog\Logger::DEBUG)],
        [new \Monolog\Processor\PsrLogMessageProcessor()]
    )
);
$client->enableDebug();
$response = $client->call('CheckDomain', ['domain' => 'example.com']);
echo $response;
```

## Todo

- [ ] More tests
- [x] Integration with Travis CI
- [ ] More methods for ClientFacade
