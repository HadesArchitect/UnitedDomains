<?php

namespace HadesArchitect\UnitedDomains;

class DnsFacadeClient extends TraceableClient
{
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
     * @param string $name
     * @param string $type
     * @param string $data
     * @param string $class
     */
    public function deleteRecord(string $zone, string $name, string $type, string $data = '', string $class = 'IN')
    {
        $this->call(
            'UpdateDNSZone',
            [
                'dnszone' => $zone,
                'delrr0'  => sprintf('%s %d %s %s %s', $name, $class, $type, $data)
            ]
        );
    }

    /**
     * @param string $zone
     * @return array
     */
    public function getRecords(string $zone)
    {
        return $this->call('QueryDNSZoneRRList', ['dnszone' => $zone])->getProperty('rr');
    }
}
