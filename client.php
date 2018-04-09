#!/usr/bin/env php
<?php

// Load dependencies
require __DIR__ . '/vendor/autoload.php';

// Set up credentials. Set you username and password for United Domains account.
$username = 'my-username';
$password = 'my-password';

// Prepare a client. We gonna use traceable client to have a good debug output. Don't forget to set your credentials!
$client = new \HadesArchitect\UnitedDomains\TraceableClient($username, $password);

// Set up a logger to see what's going on "under the hood". Optional step.
$client->setLogger(
        new \Monolog\Logger(
                'ud_api',
                [new \Monolog\Handler\StreamHandler('php://stdout', \Monolog\Logger::DEBUG)],
                [new \Monolog\Processor\PsrLogMessageProcessor()]
        )
);

// Turn on debug mode for http requests. Optional step.
//$client->enableDebug();

// Check domain direct call, existing domain
$response = $client->call('CheckDomain', ['domain' => 'ololo.com']); echo $response, PHP_EOL;

// Check domain direct call, existing domain with umlauts (UTF-8 check)
//$response = $client->call('CheckDomain', ['domain' => 'möbelhaus.de']); echo $response, PHP_EOL;

// Check domain direct call, non-existing domain
//$response = $client->call('CheckDomain', ['domain' => 'ololo345678765434567.com', 'suggest' => 2]); echo $response, PHP_EOL;

// Create a DNS Resource Record
//$response = $client->call('UpdateDNSZone', ['dnszone' => 'oc-dev.de', 'addrr0' => 'test-100500.oc-dev.de. 3600 IN A 10.10.0.2']); //echo $response, PHP_EOL;

// Create a DNS Resource Record
//$response = $client->call('UpdateDNSZone', ['dnszone' => 'oc-dev.de', 'delrr0' => 'test-100500.oc-dev.de. IN A 10.10.0.2']); //echo $response, PHP_EOL;

// Get records for a DNS zone
//$response = $client->call('QueryDNSZoneRRList', ['dnszone' => 'oc-dev.de']); //echo $response, PHP_EOL;
//var_dump($response->getProperty('rr'));

// Instead of manually sending complex queries, you can use facades whose simplify API usage. Set your credentials!
$facadeClient = new \HadesArchitect\UnitedDomains\DnsFacadeClient($username, $password);

// Get records in DNS zone
var_dump($facadeClient->getRecords('oc-dev.de'));
