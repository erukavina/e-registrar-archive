<?php

namespace Lookup;

class SSL
{
    /**
     * Attempt SSL connection for the specified host.
     *
     * @param string $host The host for SSL connection.
     * @param int $port The port for the SSL connection (default is 443).
     * @param int $timeout The timeout for the SSL connection (default is 30 seconds).
     * @return array|false An associative array containing SSL certificate information or false if the connection fails.
     */
    public static function SSL($host, $port = 443, $timeout = 30)
    {
        // Log: Attempting SSL connection for the provided host
        \Document\Log::Log("SSL: Attempting SSL connection for host: $host");

        // Create a stream context with SSL options
        $context = stream_context_create(['ssl' => ['capture_peer_cert' => true]]);

        // Suppress errors for failed connection attempts
        set_error_handler(function () { /* ignore errors */ });

        // Attempt to establish SSL connection
        \Document\Log::Log("SSL: Attemting to establish SSL connection for stream_socket_client: ssl://$host:$port");

        $socket = stream_socket_client("ssl://$host:$port", $errno, $errstr, $timeout, STREAM_CLIENT_CONNECT, $context);

        // Restore error handling
        restore_error_handler();

        if (!$socket) {
            // Log: Failed to establish SSL connection for the provided host
            \Document\Log::Log("SSL: Failed to establish SSL connection for host: $host");
            return false;
        }

        // Log: SSL connection established successfully for the provided host
        \Document\Log::Log("SSL: SSL connection established successfully for host: $host");

        // Get SSL certificate information
        $sslInfo = stream_context_get_params($socket);

        // Close the SSL connection
        fclose($socket);

        // Parse SSL certificate information recursively
        function ParseInformation($info)
        {
            foreach ($info as $key => &$value) {
                if (is_array($value)) {
                    $value = ParseInformation($value);
                }
            }
            return $info;
        }

        $parsedInfo = openssl_x509_parse($sslInfo['options']['ssl']['peer_certificate']);

        // Recursively parse SSL certificate information
        $parsedInfo = ParseInformation($parsedInfo);

        return $parsedInfo;
    }
}
