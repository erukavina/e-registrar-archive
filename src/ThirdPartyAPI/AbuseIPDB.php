<?php

/**
 * $abuseIpDb = new \tp\abuseipdb();
 * $abuseIpDb->check('118.25.6.39', 90);
 * $abuseIpDb->report('127.0.0.1', [18, 22], 'SSH login attempts with user root.', '2023-10-18T11:25:11-04:00');
 */

namespace ThirdPartyAPI;

class AbuseIPDB
{
    private const API_KEY = '?';
    private const IP_ABUSE_SERVICE = 'https://api.abuseipdb.com/api/v2/';

    /**
     * Check the reputation of an IP address using the AbuseIPDB API.
     * Docs: https://docs.abuseipdb.com
     * Author: Emanuel Tin Rukavina
     * Contact: emanuel@uncuni.com
     * Last edit: 18.01.2023.
     *
     * @param string $ipAddress The IP address to check.
     * @param int $maxAgeInDays Maximum age of the reports to consider.
     */
    public static function AbuseIPDB($ipAddress, $maxAgeInDays = 90)
    {
        // Construct the API URL for the 'check' endpoint
        $url = self::IP_ABUSE_SERVICE . 'check';

        // Set up query parameters
        $queryParams = [
            'ipAddress' => $ipAddress,
            'maxAgeInDays' => $maxAgeInDays,
            'verbose' => true,
        ];

        // Append query parameters to the URL
        $url .= '?' . http_build_query($queryParams);

        // Initialize cURL session
        $ch = curl_init($url);

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Key: ' . self::API_KEY,
            'Accept: application/json',
        ]);

        // Execute cURL session and get the response
        \Document\Log::Log("AbuseIPDB: Contacting service.");

        $response = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            \Document\Log::Log("AbuseIPDB: cURL error: ".curl_error($ch));
        } else {
            \Document\Log::Log("AbuseIPDB: Success.");

            // Decode the JSON response
            $responseData = json_decode($response, true);
            
            // Return the response
            return $responseData;
        }

        // Close cURL session
        curl_close($ch);
    }

}
