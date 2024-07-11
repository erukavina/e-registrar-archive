<?php

namespace Document;

/**
 * Log::Log("Log something");
 */
class Log
{
    // Method to add a log entry
    public static function Log($message)
    {
        // Start or resume session
        session_start();

        // Get the current time including milliseconds
        $millis = round(microtime(true) * 1000);
        $time = date('H:i:s', time()) . '.' . str_pad($millis % 1000, 3, '0', STR_PAD_LEFT);

        // Initialize logs array if not set
        if (!isset($_SESSION['logs'])) {
            $_SESSION['logs'] = "Logs started at " . $time;
        }

        // Add log message with file information to the logs array
        $_SESSION['logs'] .= "<br>" . $time . " - $message";
    }
}
