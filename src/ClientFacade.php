<?php declare(strict_types=1);

namespace HadesArchitect\UnitedDomains;

use HadesArchitect\UnitedDomains\Exception\AmbiguousRecordException;
use HadesArchitect\UnitedDomains\Exception\InvalidParameterException;
use HadesArchitect\UnitedDomains\Exception\ServerException;

class ClientFacade extends TraceableClient
{
    /**
     * @param string $domain
     * @return bool
     */
    public function isDomainFree(string $domain): bool
    {
        $response = $this->call('CheckDomain', ['domain' => $domain]);

        if ($response->getCode() === 210) {
            return true;
        } elseif ($response->getCode() === 211) {
            return false;
        }

        throw new ServerException($response, 'Unexpected response code');
    }

    /**
     * @param string $zone
     * @param string $soamname
     * @param string $soarname
     */
    public function CreateDNSZone(string $zone, string $soamname = '', string $soarname = ''): void
    {
        $this->call(
            'CreateDNSZone',
            [
                'dnszone'  => $zone,
                'soamname' => $soamname,
                'soarname' => $soarname
            ]
        );
    }

    /**
     * @param string $zone
     */
    public function DeleteDNSZone(string $zone): void
    {
        $this->call('DeleteDNSZone', ['dnszone'  => $zone]);
    }

    /**
     * @param string $zone
     * @param string $name
     * @param string $type
     * @param string $data
     * @param int $ttl
     * @param string $class
     */
    public function addRecord(string $zone, string $name, string $type, string $data, int $ttl = 3600, string $class = 'IN')
    {
        $this->call(
            'UpdateDNSZone',
            [
                'dnszone' => $zone,
                'addrr0'  => sprintf('%s %d %s %s %s', $name, $ttl, $class, $type, $data)
            ]
        );
    }

    /**
     * @param string $zone
     * @param array $records
     * @throws InvalidParameterException
     */
    public function addRecords(string $zone, array $records)
    {
        $parameters['dnszone'] = $zone;

        for ($i = 0; $i < count($records); $i++ ) {
            if (!array_key_exists('name', $records[$i]) || !array_key_exists('type', $records[$i])) {
                throw new InvalidParameterException('Missing required parameter \'name\' or \'type\'');
            }
            $diff = array_diff(array_keys($records[$i]), ['name', 'type', 'ttl', 'class', 'data'] );
            if ($diff) {
                throw new InvalidParameterException(sprintf('Unknown parameters given: %s', implode(', ', $diff)));
            }
            $parameters[sprintf('addrr%d', $i)] = sprintf(
                '%s %d %s %s %s',
                $records[$i]['name'],
                !empty($records[$i]['ttl']) ? $records[$i]['ttl'] : 3600,
                !empty($records[$i]['class']) ? $records[$i]['class'] : 'IN',
                $records[$i]['type'],
                $records[$i]['data']
            );
        }

        $this->call('UpdateDNSZone', $parameters);
    }

    /**
     * @param string $zone
     * @param string $name
     * @param string $type
     * @param string|bool $data
     * @param string $class
     */
    public function deleteRecord(string $zone, string $name, string $type, $data = false, string $class = 'IN'): void
    {
        if (false === $data) {
            $records = array_filter(
                $this->findRecordsByType($zone, $type),
                function ($record) use ($name) {
                    return $record['name'] === $name;
                }
            );

            if (count($records) !== 1) {
                throw new AmbiguousRecordException($records, 'Can\'t guess record to delete, zero or more than one match');
            }

            $class = current($records)['class'];
            $data  = current($records)['data'];
        }

        $this->call(
            'UpdateDNSZone',
            [
                'dnszone' => $zone,
                'delrr0'  => sprintf('%s %s %s %s', $name, $class, $type, $data)
            ]
        );
    }

    /**
     * @param string $zone
     * @return array
     */
    public function getRecords(string $zone)
    {
        return $this->fetchRecords($zone);
    }

    /**
     * @param string $zone
     * @param string $type
     *
     * @return array
     */
    public function findRecordsByType(string $zone, string $type)
    {
        $type = strtolower($type);

        return array_filter(
            $this->fetchRecords($zone),
            function ($record) use ($type) {
                return $type === strtolower($record['type']);
            }
        );
    }

    /**
     * @param string $zone
     * @return array
     */
    protected function fetchRecords(string $zone): array
    {
        return array_map(
            function ($record) {
                preg_match_all("/(\S+)\s(\d+)\s(\S+)\s(\S+)\s(.*)/", $record, $matches);

                return [
                    'name' => $matches[1][0],
                    'ttl' => (int)$matches[2][0],
                    'class' => $matches[3][0],
                    'type' => $matches[4][0],
                    'data' => $matches[5][0]
                ];
            },
            $this->call('QueryDNSZoneRRList', ['dnszone' => $zone])->getProperty('rr')
        );
    }
}
