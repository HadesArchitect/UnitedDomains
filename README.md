# United Domains PHP Client Library

A convenient tool to use United Domains API. It provides tools for direct API calls and convenient wrappers.

## Installation

```
composer require HadesArchitect/UnitedDomains 0.1.0
```

## Usage

### Simple Client

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

### DNS Facade Client 

When you would like to have it simpler

```php
// Client
$facadeClient = new \HadesArchitect\UnitedDomains\DnsFacadeClient($username, $password);
var_dump($facadeClient->getRecords('my-domain.com'));
```
