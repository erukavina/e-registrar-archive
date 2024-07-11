<?php
// Start the session
session_start();

// Set content type to JSON
header('Content-Type: application/json');

// Include necessary files
require_once("../../../vendor/autoload.php");
require_once '../../../src/ClassLoader.php';

// Check if the request method is POST and CSRF token is valid
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !(new \Document\CSRF())->CheckToken($_SERVER['HTTP_X_CSRF_TOKEN'])) {
    // If not, return 500 Internal Server Error
    header('HTTP/1.1 500 Internal Server Error');
    exit();
}

// Check if action parameter is provided
if ($_GET["action"] == "" || !isset($_GET["action"])) {
    // If not, return 500 Internal Server Error
    header('HTTP/1.1 500 Internal Server Error');
    exit();
}
