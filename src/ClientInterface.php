<?php

namespace HadesArchitect\UnitedDomains;

use HadesArchitect\UnitedDomains\Exception\InvalidParameterException;

interface ClientInterface extends BaseClientInterface
{
    /**
     * @param string $domain
     * @return bool
     */
    public function isDomainFree(string $domain): bool;

    /**
     * @param string $zone
     * @param string $soamname
     * @param string $soarname
     */
    public function CreateDNSZone(string $zone, string $soamname = '', string $soarname = ''): void;

    /**
     * @param string $zone
     */
    public function DeleteDNSZone(string $zone): void;

    /**
     * @param string $zone
     * @param string $name
     * @param string $type
     * @param string $data
     * @param int $ttl
     * @param string $class
     */
    public function addRecord(string $zone, string $name, string $type, string $data, int $ttl = 3600, string $class = 'IN');

    /**
     * @param string $zone
     * @param array $records
     * @throws InvalidParameterException
     */
    public function addRecords(string $zone, array $records);

    /**
     * @param string $zone
     * @param string $name
     * @param string $type
     * @param string|bool $data
     * @param string $class
     */
    public function deleteRecord(string $zone, string $name, string $type, $data = false, string $class = 'IN'): void;

    /**
     * @param string $zone
     * @return array
     */
    public function getRecords(string $zone);

    /**
     * @param string $zone
     * @param string $type
     *
     * @return array
     */
    public function findRecordsByType(string $zone, string $type);
}