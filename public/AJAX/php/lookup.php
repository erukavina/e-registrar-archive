<?php
require_once("index.php");

if ($_GET["action"] == "lookup") {
    $query = $_POST["query"];

    // Validate URL
    $validateURL = new \Validator\URL($query);
    $validationResult = $validateURL->validate();

    // Process validation results
    switch ($validationResult) {
        case 1:
            // URL is valid, proceed with lookup
            $Lookup = new \Lookup\Lookup;
            $query = strtolower(trim($query));
            // Add 'http://' if the scheme is missing
            if (!preg_match('#^https?://#', $query)) {
                $query = 'https://' . $query;
            }


            // Parse the URL
            $parsedUrl = parse_url($query);
            $Lookup->setTarget($query, $parsedUrl);

            // Perform lookup
            if ($Lookup->Lookup()) {
                echo json_encode(["tlr" => 1, "query" => $query]);
                \Document\Log::Log("AJAX/php/lookup - Lookup successful for: $query");
            } else {
                echo json_encode(["tlr" => 0, "error" => "Lookup failed."]);
                \Document\Log::Log("AJAX/php/lookup - Lookup failed for: $query");
            }
            break;
        case "EMPTY":
            $errorMessage = "Input cannot be empty.";
            echo json_encode(["tlr" => 0, "error" => $errorMessage]);
            \Document\Log::Log("AJAX/php/lookup - $errorMessage");
            break;
        case "INVALID":
            $errorMessage = "The provided URL is invalid. Example: 'https://example.com'.";
            echo json_encode(["tlr" => 0, "error" => $errorMessage]);
            \Document\Log::Log("AJAX/php/lookup - $errorMessage");
            break;
        case "GOV":
            $errorMessage = "validate_gov";
            echo json_encode(["tlr" => 0, "error" => $errorMessage]);
            \Document\Log::Log("AJAX/php/lookup - $errorMessage");
            break;
        default:
            $errorMessage = "Unknown error occurred with the provided URL.";
            echo json_encode(["tlr" => 0, "error" => $errorMessage]);
            \Document\Log::Log("AJAX/php/lookup - $errorMessage");
            break;
    }
} else {
    // Invalid action
    $errorMessage = "Invalid action";
    echo json_encode(["tlr" => 0, "error" => $errorMessage]);
    \Document\Log::Log("AJAX/php/lookup - $errorMessage");
}
