<?php

namespace Lookup;

class DNS
{
    /**
     * Perform DNS lookup for the specified host and retrieve various DNS record types.
     * Author: Emanuel Tin Rukavina <emanuel@uncuni.com>
     * 
     * @param string $host The host for DNS lookup.
     * @return array|null An associative array containing DNS records or null if the lookup fails.
     */
    public static function DNS($host)
    {
        \Document\Log::Log("Lookup\DNS - Starting DNS lookup for host $host");
        $result = array();

        // Perform DNS lookup for various record types
        self::SingleTypeDnsGetRecord($result, $host, 'A');
        self::SingleTypeDnsGetRecord($result, $host, 'CNAME');
        self::SingleTypeDnsGetRecord($result, $host, 'HINFO');
        self::SingleTypeDnsGetRecord($result, $host, 'CAA');
        self::SingleTypeDnsGetRecord($result, $host, 'MX');
        self::SingleTypeDnsGetRecord($result, $host, 'NS');
        self::SingleTypeDnsGetRecord($result, $host, 'PTR');
        self::SingleTypeDnsGetRecord($result, $host, 'SOA');
        self::SingleTypeDnsGetRecord($result, $host, 'TXT');
        self::SingleTypeDnsGetRecord($result, $host, 'AAAA');
        self::SingleTypeDnsGetRecord($result, $host, 'SRV');
        self::SingleTypeDnsGetRecord($result, $host, 'NAPTR');
        self::SingleTypeDnsGetRecord($result, $host, 'A6');
        self::SingleTypeDnsGetRecord($result, $host, 'DMARC');

        // Return the combined DNS records
        \Document\Log::Log("Lookup\DNS - Finished DNS lookup for host $host");
        return $result;
    }

    /**
     * Helper function to perform DNS lookup for a single record type and populate the result array.
     *
     * @param array $result Reference to the result array.
     * @param string $host The host for DNS lookup.
     * @param string $type The DNS record type (e.g., 'A', 'CNAME', 'MX').
     */
    private static function SingleTypeDnsGetRecord(&$result, $host, $type)
    {

        // Check if the result array for the current type is not initialized
        if (!isset($result[$type])) {
            $result[$type] = array();
        }

        // Perform DNS lookup for the specified record type
        if ($type === 'DMARC') {
            // Custom logic for DMARC lookup (replace this with your actual DMARC lookup code)
            $formattedRecord = array(
                // Populate the fields as needed for DMARC records
                // For example, 'host' => ..., 'txt' => ..., etc.
            );
            $result[$type][] = $formattedRecord;
        } else {
            // For other record types, continue using dns_get_record
            $res = dns_get_record($host, constant("DNS_$type"));

            // Iterate through each DNS record and convert it to an associative array
            foreach ($res as $record) {
                // Ensure the 'txt' field is handled correctly for TXT records
                if ($type === 'TXT') {
                    $formattedRecord = array(
                        'host' => $record['host'],
                        'class' => $record['class'],
                        'ttl' => $record['ttl'],
                        'type' => $record['type'],
                        'txt' => $record['txt'],
                        'entries' => explode(' ', $record['txt']),
                    );
                } else {
                    // For other record types, include all fields as before
                    $formattedRecord = array();
                    foreach ($record as $key => $value) {
                        $formattedRecord[$key] = $value;
                    }
                }

                // Append the formatted record to the result array for the current type
                $result[$type][] = $formattedRecord;
            }
        }
    }
}
