<?php

namespace Document;

class Database
{
    private $servername;
    private $username;
    private $password;
    private $database;

    public function __construct()
    {
        $this->servername = "localhost";
        $this->username = "username";
        $this->password = "password";
        $this->database = "database";
    }

    public function Connect()
    {
        // Attempt to establish a database connection
        $connection = new \mysqli($this->servername, $this->username, $this->password, $this->database);

        // Check if connection was successful
        if ($connection->connect_errno) {
            // Log: Failed to establish database connection
            Log::Log("Document\Database - Failed to establish database connection: " . $connection->connect_error);
            return false;
        }
        return $connection;
    }
}
