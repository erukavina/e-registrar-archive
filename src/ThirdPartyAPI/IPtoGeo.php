<?php

/**
 * Example Usage of tp\geo class:
 *
 * $hostToLookup = 'example.com';
 *
 * // Create an instance of the geo class
 * $geo = new geo();
 *
 * // Perform GEO lookup for the specified host
 * $geoData = $geo->geo($hostToLookup);
 *
 * // Output the GEO data or a message if the lookup fails
 * if ($geoData !== null) {
 *     print_r($geoData);
 * } else {
 *     echo "GEO lookup failed for host: $hostToLookup";
 * }
 */

namespace ThirdPartyAPI;

use Exception;

class IPtoGeo
{
    // Define the API URL for GEO lookup
    private const GEO_API_URL = "https://api.techniknews.net/ipgeo/";

    /**
     * Perform GEO lookup for a given host using a third-party API.
     * Author: Emanuel Tin Rukavina
     * Contact: emanuel@uncuni.com
     * Last edit: 18.01.2023.
     *
     * @param string $host The host for GEO lookup.
     * @return array|null The GEO data for the host or null if the lookup fails.
     */
    public static function IPtoGeo($host)
    {
        // Log: Starting GEO lookup for host: $host
        \Document\Log::Log("ThirdPartyAPI\IPtoGeo - Starting GEO lookup for host: $host");

        try {
            // Initialize cURL session with the GEO API URL
            $curl = curl_init(self::GEO_API_URL . $host);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $json_data = curl_exec($curl);

            // Check for cURL errors
            if (curl_errno($curl)) {
                // Get the cURL error message
                $curlErrorMessage = curl_error($curl);
                // Close the cURL session
                curl_close($curl);
                // Log: GEO lookup failed for host: $host due to cURL error
                \Document\Log::Log("ThirdPartyAPI\IPtoGeo - GEO lookup failed for host: $host due to cURL error: $curlErrorMessage");
                return null;
            }

            // Close cURL session
            curl_close($curl);

            // Decode the JSON response
            $data = json_decode($json_data, true);

            // Check if the decoding was successful and the status is "success"
            if ($data === null || $data["status"] !== "success") {
                // Log: GEO lookup failed for host: $host
                \Document\Log::Log("ThirdPartyAPI\IPtoGeo - GEO lookup failed for host: $host");
                return null;
            }

            // Log: GEO lookup successful for host: $host
            \Document\Log::Log("ThirdPartyAPI\IPtoGeo - GEO lookup successful for host: $host");
            return $data;
        } catch (Exception $e) {
            // Log: GEO lookup failed for host: $host due to exception
            \Document\Log::Log("ThirdPartyAPI\IPtoGeo - GEO lookup failed for host: $host due to exception - " . $e->getMessage() . " in file " . $e->getFile() . " on line " . $e->getLine() . "\nStack trace:\n" . $e->getTraceAsString());
            return null;
        }
    }
}
