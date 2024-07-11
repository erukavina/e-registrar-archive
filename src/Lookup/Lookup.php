<?php

namespace Lookup;

class Lookup
{
    private float $responseTimeStart;
    private float $responseTimeEnd;
    private float $executionTime;
    public string $query;
    public array $parsedUrl;
    private $conn;

    public function __construct()
    {
        // Start the response time timer
        $this->responseTimeStart = microtime(true);
        $database = new \Document\Database();

        // Obtain a database connection
        $this->conn = $database->Connect();
        \Document\Log::Log("Lookup: Starting lookup process.");
    }

    public function SetTarget($query, $parsedUrl)
    {
        // Set the query and parsed URL for the lookup
        $this->query = $query;
        $this->parsedUrl = $parsedUrl;
        \Document\Log::Log("Lookup\Lookup - Target set for query: $query, Parsed URL: " . json_encode($parsedUrl));
    }

    public function Lookup()
    {
        // Log: Starting the lookup process
        \Document\Log::Log("Lookup\Lookup - Starting lookup() method.");

        // Fetch headers for the given query URL
        $headers = \Lookup\Headers::Headers($this->query);
        // Perform WHOIS lookup for the host
        $whois = \ThirdPartyAPI\WHOIS::WHOIS($this->parsedUrl["host"]);

        $host = $whois["domain"]["domain"];
        if (empty($whois["domain"]["domain"])) {
            $host = $this->parsedUrl["host"];
        }
        if (!$headers) {
            // If unable to fetch headers, return 1 and store relevant information in session
            $response = [
                "query" => $this->query,
            ];
            if ($whois) {
                $response["whois"] = $whois;
            }
            $_SESSION["lookup"] = $response;
            // Log: Lookup completed with failure
            \Document\Log::Log("Lookup\Lookup - Completed with failure. Unable to fetch headers.");
            return 1;
        }

        // Perform DNS lookup for the host
        $dns = \Lookup\DNS::DNS($this->parsedUrl["host"]);

        // Perform SSL check for the host
        $ssl = \Lookup\SSL::SSL($this->parsedUrl["host"]);

        // Get sitemap for the host
        $sitemap = \Lookup\Sitemap::Sitemap("https://" . $whois["domain"]["domain"]);

        // Start timer and perform GEO lookup for the IP address obtained from DNS
        $geo = \ThirdPartyAPI\IPtoGeo::IPtoGeo($dns['A'][0]['ip']);

        // Perform abuseipdb lookup
        $abuseipdb = \ThirdPartyAPI\AbuseIPDB::AbuseIPDB($dns['A'][0]['ip']);

        // Package all information together
        $response = [
            "query" => $this->query,
            "host" => $host,
            "dns" => $dns,
            "whois" => $whois,
            "ssl" => $ssl,
            "geo" => $geo,
            "abuseipdb" => $abuseipdb,
            "headers" => $headers,
            "sitemap" => $sitemap
        ];

        // Sanitize problematic strings in the SSL data
        $response["ssl"] = self::SanitizeArray($response["ssl"]);

        // Calculate trust score based on response
        $trustScore = new \TrustScore\TrustScore($response);

        // End the timer and calculate execution time
        $this->responseTimeEnd = microtime(true);
        $this->executionTime = ($this->responseTimeEnd - $this->responseTimeStart) * 1000;

        // Add trust score and execution time to the response
        $response["trustScore"] = $trustScore->getScore();
        $response["exec_time"] = round($this->executionTime, 2);

        // Store the response in the session
        $_SESSION["lookup"] = $response;

        // Check if the lookup session is set and return accordingly
        if (isset($_SESSION["lookup"]) && $_SESSION["lookup"] != "") {
            $conn = $this->conn;
            $date = date("Y-m-d");
            $host = $_SESSION["lookup"]["host"];
            $trustscore = $_SESSION["lookup"]["trustScore"]["total_score"];
            $ipaddr = $_SESSION["lookup"]["geo"]["ip"];
            $countryCode = $_SESSION["document"]["geo"]["countryCode"];

            $archive = $conn->prepare("INSERT INTO ts_event (host, ts, ipaddr, browser, device) VALUES (?, ?, ?, ?, ?)");
            $archive->bind_param("sssss", $host, $trustscore, $ipaddr, $date,$date);
            $archive->execute();
            // Log: Lookup completed successfully
            \Document\Log::Log("Lookup\Lookup - Completed successfully. Result stored in session.");
            return 1;
        } else {
            // Log: Lookup failed
            \Document\Log::Log("Lookup\Lookup - Completed with failure. Unable to store result in session.");
            return 0;
        }
    }

    private function SanitizeString($str)
    {
        // Remove non-printable characters and convert to UTF-8 encoding
        $str = mb_convert_encoding(preg_replace('/[[:^print:]]/', '', $str), 'UTF-8', 'UTF-8');
        return $str;
    }

    private function SanitizeArray($array)
    {
        if (is_array($array)) {
            // Iterate through array elements and sanitize each one
            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    // If value is an array, recursively sanitize it
                    $array[$key] = $this->sanitizeArray($value);
                } else {
                    // If value is a string, sanitize it
                    $array[$key] = $this->SanitizeString($value);
                }
            }
        } elseif (is_string($array)) {
            // If input is a string, sanitize it
            $array = $this->SanitizeString($array);
        }

        return $array;
    }
}
