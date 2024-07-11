<h4>Ownership Information (WHOIS)</h4>
<a wiki-data="WHOIS" data-bs-toggle="offcanvas" href="#wiki_canvas"><i class="bi bi-question-circle"></i> What is WHOIS?</a>
<br>
<br>
<?php
// Function to print formatted data
function printFormattedData($title, $data)
{
    echo "<div class='border rounded m-1 p-2'>";
    echo "<h6>" . htmlspecialchars($title) . "</h6>";

    if (empty($data)) {
        echo "WHOIS data not provided";
    } else {
        foreach ($data as $item) {
            foreach ($item as $key => $value) {
                if ($value === null || $value === '') {
                    continue;
                }

                $formattedKey = ucfirst(str_replace('_', ' ', $key));
                echo "<b>" . htmlspecialchars($formattedKey) . "</b>: ";

                switch ($key) {
                    case 'email':
                        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            echo "<a target='_blank' href='mailto:" . htmlspecialchars($value) . "'>Email address</a>";
                        } else if (filter_var($value, FILTER_VALIDATE_URL)) {
                            echo "<a target='_blank' href='$value'>Request</a>";
                        } else {
                            // Extract the link from the email string
                            preg_match('/(?<=at )https?:\/\/[^\s]+/', $value, $matches);
                            $link = isset($matches[0]) ? htmlspecialchars($matches[0]) : '';

                            // Display link with appropriate text
                            if (!empty($link)) {
                                echo "<a target='_blank' href='$link'>Request</a>";
                            } else {
                                echo htmlspecialchars($value);
                            }
                        }
                        break;

                    case 'referral_url':
                        echo "<a target='_blank' href='$value'>Website</a>";
                        break;
                    case 'status':
                    case 'name_servers':
                        echo "<ul>";
                        foreach ($value as $val) {
                            echo "<li><i>" . htmlspecialchars($val) . "</i></li>";
                        }
                        echo "</ul>";
                        break;
                    case 'created_date':
                    case 'updated_date':
                    case 'expiration_date':
                        echo formatHumanReadableDate($value);
                        break;
                    default:
                        echo htmlspecialchars($value);
                        break;
                }
                echo "<br>";
            }
        }
    }

    echo "</div>";
}

// Function to format human-readable date
function formatHumanReadableDate($dateString)
{
    return date("d.m.Y.", strtotime($dateString));
}

// Function to display registry info
function displayRegistryInfo($domainData)
{
    if (isset($domainData['id'])) {
        echo "<b>Domain ID:</b> " . htmlspecialchars($domainData['id']) . "<br>";
    }

    if (isset($domainData['created_date'])) {
        echo "<b>Registered:</b> " . formatHumanReadableDate($domainData['created_date']) . "<br>";
    }

    if (isset($domainData['updated_date'])) {
        echo "<b>Last updated:</b> " . formatHumanReadableDate($domainData['updated_date']) . "<br>";
    }

    if (isset($domainData['expiration_date'])) {
        echo "<b>Expires:</b> " . formatHumanReadableDate($domainData['expiration_date']) . "<br>";
    }
}

// Display WHOIS data sections
$sections = [
    "registrant" => "Registered by",
    "administrative" => "Administrative",
    "technical" => "Technical",
    "registrar" => "Registered at",
    "domain" => "Registry info"
];

foreach ($sections as $key => $title) {
    $data = isset($_SESSION["lookup"]["whois"][$key]) ? [$_SESSION["lookup"]["whois"][$key]] : [];
    printFormattedData("$title", $data);
}

// Display raw WHOIS record
echo "<div class='border rounded m-1 p-2'>";
echo "<h6><u>Raw WHOIS record:</u></h6>";
echo "<pre style='background-color: var(--bs-body-bg);padding: 1em;overflow: auto;max-height: 20em;'>";

if (isset($_SESSION["lookup"]["whois"])) {
    $whois = $_SESSION["lookup"]["whois"];
    foreach ($whois as $section => $data) {
        echo "<b>" . htmlspecialchars($section) . "</b>: " . htmlspecialchars(json_encode($data, JSON_PRETTY_PRINT)) . "<br>";
    }
} else {
    echo "WHOIS data not provided";
}

echo "</pre>";
echo "</div>";

?>