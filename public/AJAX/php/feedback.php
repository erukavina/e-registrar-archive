<?php
// Start the session
session_start();

// Set content type to JSON
header('Content-Type: application/json');
// Check if the request method is POST and CSRF token is valid
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // If not, return 500 Internal Server Error
    header('HTTP/1.1 500 Internal Server Error');
    return 0;
    exit();
}
$repo_owner = "uncuni";
$repo_name = "e-Registrar";
$issue_title = "[" . $_POST["reason"] . "] - " . $_POST["msg"];
// GitHub access token with repo scope
$access_token = "token";
// Session data
$session_data = isset($_SESSION["logs"]) ? $_SESSION["logs"] : '';
$identity_data = isset($_SESSION["identity"]) ? json_encode($_SESSION["identity"], JSON_PRETTY_PRINT) : 'User is not logged in.';    // Format the issue body to include session data
$issue_body = "<pre>" . $identity_data . "</pre>";
$issue_body .= "\n -------------- \n";
$issue_body .= $session_data;

// Create issue payload
$data = array(
    'title' => $issue_title,
    'body' => $issue_body
);
// Encode payload as JSON
$payload = json_encode($data);

// Create and configure cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.github.com/repos/$repo_owner/$repo_name/issues");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Authorization: token $access_token",
    "Content-Type: application/json",
    "User-Agent: e-Registrar"
));
// Execute cURL request
$response = curl_exec($ch);

// Check for errors
if ($response === false) {
    // Error handling
    echo json_encode(["tlr" => 0]);
} else {
    // Check response status code
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($http_code == 201) {
        echo json_encode(["tlr" => 1]);
    } else {
        // Error handling
        echo json_encode(["tlr" => 0]);
    }
}

// Close cURL session
curl_close($ch);
return 0;
