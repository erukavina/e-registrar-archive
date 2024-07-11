<?php

namespace Lookup;

class Headers
{
    /**
     * Fetches the headers of a given URL.
     *
     * @param string $url The URL to fetch headers from.
     * @return array|false An associative array containing the headers, or false if the request fails.
     */
    public static function Headers($url)
    {
        \Document\Log::Log("Lookup\Headers - Starting to fetch for URL: $url");
        $userAgent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);

        $response = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($statusCode === 0 || $response === false) {
            \Document\Log::Log("Lookup\Headers - Failed to fetch headers for URL: $url");
            return false;
        }

        \Document\Log::Log("Lookup\Headers - Fetched successfully for URL: $url");
        // Parsing headers and body
        list($headers, $body) = explode("\r\n\r\n", $response, 2);

        // Parsing status line
        $statusLine = strtok($headers, "\r\n");

        // Parsing headers into an associative array
        $headersArray = [];
        $lines = explode("\r\n", $headers);
        foreach ($lines as $line) {
            $parts = explode(':', $line, 2);
            if (count($parts) === 2) {
                // Convert header name to lowercase
                $headerName = strtolower(trim($parts[0]));
                if (!isset($headersArray[$headerName])) {
                    $headersArray[$headerName] = trim($parts[1]);
                } else {
                    // If header already exists, convert it into an array
                    if (!is_array($headersArray[$headerName])) {
                        $headersArray[$headerName] = [$headersArray[$headerName]];
                    }
                    $headersArray[$headerName][] = trim($parts[1]);
                }
            }
        }

        // Add status line to the headers array
        $headersArray['status_line'] = $statusLine;

        // Add body to the headers array
        $headersArray['body'] = $body;

        \Document\Log::Log("Lookup\Headers - Returning array after parsing for URL: $url");
        return $headersArray;
    }
}
