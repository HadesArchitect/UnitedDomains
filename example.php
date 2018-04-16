#!/usr/bin/env php
<?php

// Load dependencies
require __DIR__ . '/vendor/autoload.php';

// Set up credentials. Set you username and password for United Domains account.
$username = 'username';
$password = 'password';

// Prepare a client. We gonna use traceable client to have a good debug output.
$client = new \HadesArchitect\UnitedDomains\TraceableClient($username, $password);

// Optional step. Set up a logger to see what's going on "under the hood".
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
//$response = $client->call('CheckDomain', ['domain' => 'ololo.com']); echo $response, PHP_EOL;

// Check domain direct call, existing domain with umlauts (UTF-8 check)
//$response = $client->call('CheckDomain', ['domain' => 'möbelhaus.de']); echo $response, PHP_EOL;

// Check domain direct call, non-existing domain
//$response = $client->call('CheckDomain', ['domain' => 'ololo345678765434567.com', 'suggest' => 2]); echo $response, PHP_EOL;

// Create a DNS Resource Record
//$response = $client->call('UpdateDNSZone', ['dnszone' => 'example.de', 'addrr0' => 'test-100500.example.de. 3600 IN A 10.10.0.2']); //echo $response, PHP_EOL;

// Create a DNS Resource Record
//$response = $client->call('UpdateDNSZone', ['dnszone' => 'example.de', 'delrr0' => 'test-100500.example.de. IN A 10.10.0.2']); //echo $response, PHP_EOL;

// Get records for a DNS zone
//$response = $client->call('QueryDNSZoneRRList', ['dnszone' => 'example.de']); //echo $response, PHP_EOL;
//var_dump($response->getProperty('rr'));

// Instead of manually sending complex queries, you can use facades whose simplify API usage. Set your credentials!
$facadeClient = new \HadesArchitect\UnitedDomains\ClientFacade($username, $password);

$facadeClient->setLogger(
    new \Monolog\Logger(
        'ud_api',
        [new \Monolog\Handler\StreamHandler('php://stdout', \Monolog\Logger::DEBUG)],
        [new \Monolog\Processor\PsrLogMessageProcessor()]
    )
);

// Get records in DNS zone
//var_dump($facadeClient->getRecords('example.de'));

// Get A-type records in DNS zone
//var_dump($facadeClient->findRecordsByType('example.de', 'A'));

// Check domain availability
//if ($facadeClient->isDomainFree('example.com'))
//    echo 'Domain example.com is free, lolwut?', PHP_EOL;
//else
//    echo 'Domain example.com is registered already.', PHP_EOL;
//if ($facadeClient->isDomainFree('1234567834567654376543.com'))
//    echo 'Domain 1234567834567654376543.com is free', PHP_EOL;
//else
//    echo 'Domain 1234567834567654376543.com is registered already.', PHP_EOL;

// Create DNS Zone
//$facadeClient->CreateDNSZone('test999999999.de');

// Delete DNS Zone
//$facadeClient->DeleteDNSZone('test999999999.de');

// Add multiply records
//$facadeClient->addRecords(
//        'example.de',
//        [
//            ['name' => 'test1.example.de', 'type' => 'A', 'data' => '8.8.8.8'],
//            ['name' => 'test2.example.de', 'type' => 'A', 'data' => '87.87.87.87']
//        ]
//);

// Delete record by name and type only, without specifying data
//$facadeClient->deleteRecord('example.de', 'test3.example.de.', 'A');