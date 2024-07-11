<?php

// Example usage:
/**
 * $hostToLookup = 'example.com';
 *
 * // Create an instance of the whois class
 * $whois = new whois();
 *
 * // Perform WHOIS lookup for the specified host
 * $whoisData = $whois->lookup($hostToLookup);
 *
 * // Output the WHOIS data or a message if the lookup fails
 * if ($whoisData !== null) {
 *     print_r($whoisData);
 * } else {
 *     echo "WHOIS lookup failed for host: $hostToLookup";
 * }
 */

namespace ThirdPartyAPI;

use Exception, log;

class WHOIS
{
    /**
     * Perform WHOIS lookup for the specified host.
     * Author: Emanuel Tin Rukavina
     * Contact: emanuel@uncuni.com
     * Last edit: 18.01.2023.
     * 
     * @param string $host The host for WHOIS lookup.
     * @return array|null The result of the WHOIS lookup or null if the lookup fails.
     */
    public static function WHOIS($host)
    {
        // Log: Starting WHOIS lookup for host: $host
        \Document\Log::Log("WHOIS: Starting WHOIS lookup for host: $host");
        
        try {
            // Initialize cURL session with the WHOIS API URL
            $curl = curl_init("https://scraper.run/whois?addr=" . $host);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            // Execute cURL session and get the response
            $json_data = curl_exec($curl);

            // Check for cURL errors
            if (curl_errno($curl)) {
                // Get the cURL error message
                $curlErrorMessage = curl_error($curl);
                // Close the cURL session
                curl_close($curl);
                // Log: Failed to perform WHOIS lookup for host: $host due to cURL error: $curlErrorMessage
                \Document\Log::Log("WHOIS: Failed to perform WHOIS lookup for host: $host due to cURL error: $curlErrorMessage");
                throw new Exception("Failed to perform WHOIS lookup for host: $host");
            }

            // Close cURL session
            curl_close($curl);

            // Decode the JSON response
            $data = json_decode($json_data, true);

            // Check if the decoding was successful
            if ($data === null) {
                // Log: WHOIS lookup failed for host: $host
                \Document\Log::Log("WHOIS: WHOIS lookup failed for host: $host");
                return null;
            }

            // Log: WHOIS lookup successful for host: $host
            \Document\Log::Log("WHOIS: WHOIS lookup successful for host: $host");

            return $data;
        } catch (Exception $e) {
            // Log: Exception caught during WHOIS lookup - Error: {$e->getMessage()}
            \Document\Log::Log("WHOIS: Exception caught during WHOIS lookup - Error: {$e->getMessage()}");
            return null;
        }
    }
}

?>
