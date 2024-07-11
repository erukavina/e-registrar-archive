<?php

/*
 * Generate trust score
 * 
 * Author: Antonio Peter
 * Contact: antonio.peter.dev@gmail.com
 * 
 */

namespace TrustScore;

use DateTime;

class TrustScore
{
	private $response;          // Holds the response data
	private $totalScore;        // Total trust score across all categories
	private $categoryScores;    // Scores for each category(examples: dns score, ssl score...)
	private $criteria;          // Criteria for each category("sslLevel", "domainAge"....)
	private $criterionScores;   // Scores and explanations for each criterion
	private $CRITERION_DETAILS;
	private $dynamicValues = []; // Values that will be injected into the final explanations before being sent to the frontend

	/**
	 * Constructor for TrustScore class.
	 * 
	 * @param array $response - The response data containing information related to SSL, DNS, etc.
	 */
	public function __construct($response)
	{
		$this->response = $response;
		$this->totalScore = 0;
		$this->categoryScores = [];
		// Define criteria for each category
		$this->criteria = [
			'ssl' => ["ssl_level"],                     // SSL score category with a criterion called "ssl_level"
			'dns' => ["phishing_protection"],       // DNS score category with two criteria called "domain_age" and "something"
			'reputation' => ["abuseipdb"],
			'headers' => ["csp", "http_security_headers", "cookie_security", "cors", "cache_control"],
			'whois' => ["whois_email", "whois_guard", "domain_age"],
			// Add more categories and criteria
		];
		$this->criterionScores = [];

		$trustScoreExplanationsInterface = new TRUSTSCORE_EXPLANATIONS();
		$this->CRITERION_DETAILS = $trustScoreExplanationsInterface->CRITERION_DETAILS;
	}

	/**
	 * Get the overall trust score, category scores, and criterion scores.
	 * 
	 * @return array - An associative array containing the total score, category scores, and criterion scores.
	 */
	public function getScore()
	{
		// Calculate the score for each category
		foreach ($this->criteria as $category => $criterionList) {
			$this->calculateCategoryScore($category, $criterionList);
		}

		// Check for disqualifying or uncertainty criteria in any category
		$this->checkDisqualifyingCriteria();
		$this->checkUncertaintyCriteria();

		$this->injectDynamicValuesIntoCriterionScores();

		\Document\Log::Log("END TRUSTSCORE");
		// Return the total score and category scores
		return [
			'total_score' => $this->totalScore,
			'category_scores' => $this->categoryScores,
		];
	}

	/**
	 * Calculate the score for a specific category based on its criteria.
	 * 
	 * @param string $category - The category for which the score is calculated.
	 * @param array $criterionList - The list of criteria for the given category.
	 */
	private function calculateCategoryScore($category, $criterionList)
	{
		$categoryScore = 0;
		$categoryScoresAndExplanations = [];

		// Calculate the score for each criterion in the category
		foreach ($criterionList as $criterion) {
			$criterionScore = $this->calculateCriterionScore($category, $criterion);
			$categoryScore += $criterionScore;
			$categoryScoresAndExplanations[$criterion] = $this->criterionScores[$category][$criterion];
		}

		// Update total score and category score
		$this->totalScore += $categoryScore;
		$this->categoryScores[$category] = [
			'category_score' => $categoryScore,
			'criterions' => $categoryScoresAndExplanations,
		];
	}

	/**
	 * Calculate the score for a specific criterion within a category.
	 * 
	 * @param string $category - The category to which the criterion belongs.
	 * @param string $criterion - The criterion for which the score is calculated.
	 * 
	 * @return int - The calculated score for the criterion.
	 */
	private function calculateCriterionScore($category, $criterion)
	{

		// Evaluate each criterion based on the provided logic
		switch ($criterion) {
			case 'ssl_level':
				$domainOrganization = "None";

				// CRITERION LOGIC: Check SSL level and provide a score and explanation
				if (!isset($this->response["ssl"]["serialNumber"])) {
					// No SSL certificate found
					$criterionCode = "SSL_LEVEL_NOSSL";
				} elseif (0 /* implement logic for free SSL */) {
					// Free SSL certificate found
					$criterionCode = "SSL_LEVEL_FREESSL";
				} elseif (isset($this->response["ssl"]["subject"]["O"])) {
					// Organization-validated SSL certificate found
					$this->dynamicValues["SSL-ORG"] = $this->response["ssl"]["subject"]["O"];
					$criterionCode = "SSL_LEVEL_OVSSL";
				} elseif (1 /* implement logic for domain-validated SSL */) {
					$criterionCode = "SSL_LEVEL_DVSSL";
				} else {
					// Default case if SSL data is not available
					$criterionCode = "SSL_LEVEL_UNKNOWN";
				}

				// Save the score and explanation for the criterion
				$this->criterionScores[$category][$criterion] = [
					'score' => $this->CRITERION_DETAILS[$criterionCode]["score"],
					'maxScore' => $this->CRITERION_DETAILS[$criterionCode]["maxScore"],
					'basicExplanation' => $this->CRITERION_DETAILS[$criterionCode]["basicExplanation"],
					'basicExplanationDetail' => $this->CRITERION_DETAILS[$criterionCode]["basicExplanationDetail"],
					'premiumExplanation' => $this->CRITERION_DETAILS[$criterionCode]["premiumExplanation"],
					'premiumExplanationDetail' => $this->CRITERION_DETAILS[$criterionCode]["premiumExplanationDetail"],
					'devExplanation' => $this->CRITERION_DETAILS[$criterionCode]["devExplanation"],
					'devExplanationDetail' => $this->CRITERION_DETAILS[$criterionCode]["devExplanationDetail"],
				];
				break;



			case 'whois_email':
				// CRITERION LOGIC: Check if WHOIS record contains a contact email address
				if (isset($this->response["whois"]["registrant"]["email"])) {
					// No email record found
					$criterionCode = "WHOIS_EMAIL_EMAILPRESENT";
				} else {
					// Email record found
					$criterionCode = "WHOIS_EMAIL_NOEMAIL";
				}


				// Save the score and explanation for the criterion
				$this->criterionScores[$category][$criterion] = [
					'score' => $this->CRITERION_DETAILS[$criterionCode]["score"],
					'maxScore' => $this->CRITERION_DETAILS[$criterionCode]["maxScore"],
					'basicExplanation' => $this->CRITERION_DETAILS[$criterionCode]["basicExplanation"],
					'basicExplanationDetail' => $this->CRITERION_DETAILS[$criterionCode]["basicExplanationDetail"],
					'premiumExplanation' => $this->CRITERION_DETAILS[$criterionCode]["premiumExplanation"],
					'premiumExplanationDetail' => $this->CRITERION_DETAILS[$criterionCode]["premiumExplanationDetail"],
					'devExplanation' => $this->CRITERION_DETAILS[$criterionCode]["devExplanation"],
					'devExplanationDetail' => $this->CRITERION_DETAILS[$criterionCode]["devExplanationDetail"],
				];
				break;


			case 'whois_guard':
				// CRITERION LOGIC: Check if whois record contains an email address
				if (isset($this->response["whois"]["registrant"]["id"])) {
					$registrantId = strtolower($this->response["whois"]["registrant"]["id"]);
					$privacyKeywords = array("privacy", "protected", "redacted", "guard", "not available", "hidden");

					// Default assumption
					$criterionCode = "WHOIS_GUARD_FALSE";

					foreach ($privacyKeywords as $keyword) {
						if (strpos($registrantId, $keyword) !== false) {
							$criterionCode = "WHOIS_GUARD_TRUE";
							break;  // Stop searching once a match is found
						}
					}
				} else {
					// Failed to get whois data id field
					// Assume everything is ok
					$criterionCode = "WHOIS_GUARD_FALSE";
				}

				// Save the score and explanation for the criterion
				$this->criterionScores[$category][$criterion] = [
					'score' => $this->CRITERION_DETAILS[$criterionCode]["score"],
					'maxScore' => $this->CRITERION_DETAILS[$criterionCode]["maxScore"],
					'basicExplanation' => $this->CRITERION_DETAILS[$criterionCode]["basicExplanation"],
					'basicExplanationDetail' => $this->CRITERION_DETAILS[$criterionCode]["basicExplanationDetail"],
					'premiumExplanation' => $this->CRITERION_DETAILS[$criterionCode]["premiumExplanation"],
					'premiumExplanationDetail' => $this->CRITERION_DETAILS[$criterionCode]["premiumExplanationDetail"],
					'devExplanation' => $this->CRITERION_DETAILS[$criterionCode]["devExplanation"],
					'devExplanationDetail' => $this->CRITERION_DETAILS[$criterionCode]["devExplanationDetail"],
				];
				break;


			case 'domain_age':
				// CRITERION LOGIC: Calculate domain age in years and provide a score and explanation
				if (empty($this->response["whois"]["domain"]["created_date"])) {
					$criterionCode = "WHOIS_DOMAINAGE_UNKNOWN";
				} else {
					$domainCreated = new DateTime($this->response["whois"]["domain"]["created_date"]);
					$currentDateTime = new DateTime();

					$interval = $domainCreated->diff($currentDateTime);

					$domainAgeInYears = $interval->y;
					$domainAgeInMonths = $domainAgeInYears * 12 + $interval->m;
					$domainAgeInWeeks = floor($interval->days / 7);

					if ($domainAgeInWeeks < 1) {
						$criterionCode = "WHOIS_DOMAINAGE_7DAYS";
						$criterionScore = -100;
					} elseif ($domainAgeInMonths < 1) {
						$criterionCode = "WHOIS_DOMAINAGE_MONTH";
						$criterionScore = 1;
					} elseif ($domainAgeInMonths < 6) {
						$criterionCode = "WHOIS_DOMAINAGE_SIXMONTHS";
						$criterionScore = 2;
					} elseif ($domainAgeInYears > 6) {
						$criterionCode = "WHOIS_DOMAINAGE_OVER6YEARS";
						$criterionScore = 10;
					} elseif ($domainAgeInYears > 5) {
						$criterionCode = "WHOIS_DOMAINAGE_OVER5YEARS";
						$criterionScore = 9;
					} elseif ($domainAgeInYears > 4) {
						$criterionCode = "WHOIS_DOMAINAGE_OVER4YEARS";
						$criterionScore = 8;
					} elseif ($domainAgeInYears > 3) {
						$criterionCode = "WHOIS_DOMAINAGE_OVER_3YEARS";
						$criterionScore = 7;
					} elseif ($domainAgeInYears > 2) {
						$criterionCode = "WHOIS_DOMAINAGE_OVER2YEARS";
						$criterionScore = 6;
					} elseif ($domainAgeInYears > 1) {
						$criterionCode = "WHOIS_DOMAINAGE_OVER1YEAR";
						$criterionScore = 5;
					} else {
						$criterionCode = "WHOIS_DOMAINAGE_UNKNOWN";
						$criterionScore = -1;
					}
				}

				// Save the score and explanation for the criterion
				$this->criterionScores[$category][$criterion] = [
					'score' => $this->CRITERION_DETAILS[$criterionCode]["score"],
					'maxScore' => $this->CRITERION_DETAILS[$criterionCode]["maxScore"],
					'basicExplanation' => $this->CRITERION_DETAILS[$criterionCode]["basicExplanation"],
					'basicExplanationDetail' => $this->CRITERION_DETAILS[$criterionCode]["basicExplanationDetail"],
					'premiumExplanation' => $this->CRITERION_DETAILS[$criterionCode]["premiumExplanation"],
					'premiumExplanationDetail' => $this->CRITERION_DETAILS[$criterionCode]["premiumExplanationDetail"],
					'devExplanation' => $this->CRITERION_DETAILS[$criterionCode]["devExplanation"],
					'devExplanationDetail' => $this->CRITERION_DETAILS[$criterionCode]["devExplanationDetail"],
				];
				break;


			case 'abuseipdb':
				$reports = $this->response["abuseipdb"]["data"]["reports"];

				// Define custom category settings (threshold as a percentage and score)

				$customCategorySettings = [
					1 => ['threshold' => 0.05, 'criterionCode' => "ABUSEIPDB_DNS_COMPROMISE"],
					4 => ['threshold' => 0.1, 'criterionCode' => "ABUSEIPDB_DDOS_ATTACK"],
					6 => ['threshold' => 0.05, 'criterionCode' => "ABUSEIPDB_PING_OF_DEATH"],
					7 => ['threshold' => 0.1, 'criterionCode' => "ABUSEIPDB_PHISHING"],
					10 => ['threshold' => 0.1, 'criterionCode' => "ABUSEIPDB_WEB_SPAM"],
					11 => ['threshold' => 0.1, 'criterionCode' => "ABUSEIPDB_EMAIL_SPAM"],
					14 => ['threshold' => 0.05, 'criterionCode' => "ABUSEIPDB_PORT_SCAN"],
					18 => ['threshold' => 0.1, 'criterionCode' => "ABUSEIPDB_BRUTE_FORCE"],
					20 => ['threshold' => 0.05, 'criterionCode' => "ABUSEIPDB_EXPLOITED_HOST"],
					21 => ['threshold' => 0.05, 'criterionCode' => "ABUSEIPDB_WEB_APP_ATTACK"],
					22 => ['threshold' => 0.1, 'criterionCode' => "ABUSEIPDB_SSH"],
				];

				/*
				$customCategorySettings =  [];
				$customCategorySettings = [
					1 => ['threshold' => 0.15, 'criterionCode' => "ABUSEIPDB_DANGEROUSREPORTS"], // DNS Compromise
					2 => ['threshold' => 0.7, 'criterionCode' => "ABUSEIPDB_REPORTS"], // DNS Poisoning
					3 => ['threshold' => 0.7, 'criterionCode' => "ABUSEIPDB_REPORTS"], // Fraud Orders
					4 => ['threshold' => 0.15, 'criterionCode' => "ABUSEIPDB_DANGEROUSREPORTS"], // DDoS Attack
					5 => ['threshold' => 0.7, 'criterionCode' => "ABUSEIPDB_REPORTS"], // FTP Brute-Force
					6 => ['threshold' => 0.15, 'criterionCode' => "ABUSEIPDB_DANGEROUSREPORTS"], // Ping of Death
					7 => ['threshold' => 0.15, 'criterionCode' => "ABUSEIPDB_DANGEROUSREPORTS"], // Phishing
					8 => ['threshold' => 0.7, 'criterionCode' => "ABUSEIPDB_REPORTS"], // Fraud VoIP
					9 => ['threshold' => 0.7, 'criterionCode' => "ABUSEIPDB_REPORTS"], // Open Proxy
					10 => ['threshold' => 0.7, 'criterionCode' => "ABUSEIPDB_REPORTS"], // Web Spam
					11 => ['threshold' => 0.7, 'criterionCode' => "ABUSEIPDB_REPORTS"], // Email Spam
					12 => ['threshold' => 0.7, 'criterionCode' => "ABUSEIPDB_REPORTS"], // Blog Spam
					13 => ['threshold' => 0.7, 'criterionCode' => "ABUSEIPDB_REPORTS"], // VPN IP
					14 => ['threshold' => 0.7, 'criterionCode' => "ABUSEIPDB_REPORTS"], // Port Scan
					15 => ['threshold' => 0.7, 'criterionCode' => "ABUSEIPDB_REPORTS"], // Hacking
					16 => ['threshold' => 0.7, 'criterionCode' => "ABUSEIPDB_REPORTS"], // SQL Injection
					17 => ['threshold' => 0.7, 'criterionCode' => "ABUSEIPDB_REPORTS"], // Spoofing
					18 => ['threshold' => 0.15, 'criterionCode' => "ABUSEIPDB_DANGEROUSREPORTS"], // Brute-Force
					19 => ['threshold' => 0.7, 'criterionCode' => "ABUSEIPDB_REPORTS"], // Bad Web Bot
					20 => ['threshold' => 0.15, 'criterionCode' => "ABUSEIPDB_DANGEROUSREPORTS"], // Exploited Host
					21 => ['threshold' => 0.7, 'criterionCode' => "ABUSEIPDB_REPORTS"], // Web App Attack
					22 => ['threshold' => 0.7, 'criterionCode' => "ABUSEIPDB_REPORTS"], // SSH
					23 => ['threshold' => 0.7, 'criterionCode' => "ABUSEIPDB_REPORTS"], // IoT Targeted
				];
*/


				// Count occurrences of each category
				if ($reports !== null) {
					$totalReports = count($reports);
				} else {
					// Handle the case when $reports is null
					$totalReports = 0; // Or any default value you prefer
				}

				$categoryCounts = [];

				foreach ($reports as $report) {
					foreach ($report['categories'] as $abuseCategory) {
						if (isset($categoryCounts[$abuseCategory])) {
							$categoryCounts[$abuseCategory]++;
						} else {
							$categoryCounts[$abuseCategory] = 1;
						}
					}
				}


				$criterionCode = "ABUSEIPDB_NOREPORTS";



				// Iterate through category counts and determine the criterion code
				foreach ($categoryCounts as $abuseCategory => $count) {
					if (isset($customCategorySettings[$abuseCategory])) {
						$settings = $customCategorySettings[$abuseCategory];
						$thresholdPercentage = $settings['threshold'];
						$criterionCode = $settings['criterionCode'];
						$threshold = $totalReports * $thresholdPercentage;

						if ($count >= $threshold) {
							break;
						}
					}
				}

				// Check abuse confidence score and assign criterion code accordingly
				$abuseConfidenceScoreThreshold = 50;

				if ($this->response["abuseipdb"]["data"]["abuseConfidenceScore"] > $abuseConfidenceScoreThreshold) {
					$criterionCode = "ABUSEIPDB_HIGHSCORE";
				}

				// Save the criterion code
				$this->criterionScores[$category][$criterion] = [
					'score' => $this->CRITERION_DETAILS[$criterionCode]["score"],
					'maxScore' => $this->CRITERION_DETAILS[$criterionCode]["maxScore"],
					'basicExplanation' => $this->CRITERION_DETAILS[$criterionCode]["basicExplanation"],
					'basicExplanationDetail' => $this->CRITERION_DETAILS[$criterionCode]["basicExplanationDetail"],
					'premiumExplanation' => $this->CRITERION_DETAILS[$criterionCode]["premiumExplanation"],
					'premiumExplanationDetail' => $this->CRITERION_DETAILS[$criterionCode]["premiumExplanationDetail"],
					'devExplanation' => $this->CRITERION_DETAILS[$criterionCode]["devExplanation"],
					'devExplanationDetail' => $this->CRITERION_DETAILS[$criterionCode]["devExplanationDetail"],
				];
				break;



			case "csp":
				// CRITERION LOGIC: Check for any header containing the substring "content-security-policy" and provide a criterion code
				$containsCSP = false;
				foreach ($this->response["headers"] as $headerName => $headerValue) {
					if (stripos($headerName, "content-security-policy") !== false) {
						$containsCSP = true;
						break;
					}
				}

				if ($containsCSP) {
					$criterionCode = "HEADERS_CSP_HASCSP";
				} else {
					$criterionCode = "HEADERS_CSP_NOCSP";
				}


				// Save the criterion code
				$this->criterionScores[$category][$criterion] = [
					'score' => $this->CRITERION_DETAILS[$criterionCode]["score"],
					'maxScore' => $this->CRITERION_DETAILS[$criterionCode]["maxScore"],
					'basicExplanation' => $this->CRITERION_DETAILS[$criterionCode]["basicExplanation"],
					'basicExplanationDetail' => $this->CRITERION_DETAILS[$criterionCode]["basicExplanationDetail"],
					'premiumExplanation' => $this->CRITERION_DETAILS[$criterionCode]["premiumExplanation"],
					'premiumExplanationDetail' => $this->CRITERION_DETAILS[$criterionCode]["premiumExplanationDetail"],
					'devExplanation' => $this->CRITERION_DETAILS[$criterionCode]["devExplanation"],
					'devExplanationDetail' => $this->CRITERION_DETAILS[$criterionCode]["devExplanationDetail"],
				];
				break;


			case 'phishing_protection':
				// CRITERION LOGIC: Check for phishing protection (DMARC, SPF records) and provide a criterion code
				$dmarcProtection = false;
				$spfProtection = false;

				// Check for DMARC record
				if (!empty($this->response["dns"]["TXT"])) {
					foreach ($this->response["dns"]["TXT"] as $txtRecord) {
						// Convert $txtRecord to a string before using strpos
						$txtRecordString = is_array($txtRecord) ? implode(" ", $txtRecord) : $txtRecord;

						if (strpos($txtRecordString, "v=DMARC1") !== false) {
							$dmarcProtection = true;
							break;
						}
					}
				}

				// Check for SPF record
				if (!empty($this->response["dns"]["TXT"])) {
					foreach ($this->response["dns"]["TXT"] as $txtRecord) {
						// Convert $txtRecord to a string before using strpos
						$txtRecordString = is_array($txtRecord) ? implode(" ", $txtRecord) : $txtRecord;

						if (strpos($txtRecordString, "v=spf1") !== false) {
							$spfProtection = true;
							break;
						}
					}
				}

				// Assign criterion codes based on priority
				if ($dmarcProtection) {
					$criterionCode = "DNS_PHISHING_DMARC";
				} elseif ($spfProtection) {
					$criterionCode = "DNS_PHISHING_SPF";
				} else {
					$criterionCode = "DNS_PHISHING_NONE";
				}

				// Save the criterion code
				$this->criterionScores[$category][$criterion] = [
					'score' => $this->CRITERION_DETAILS[$criterionCode]["score"],
					'maxScore' => $this->CRITERION_DETAILS[$criterionCode]["maxScore"],
					'basicExplanation' => $this->CRITERION_DETAILS[$criterionCode]["basicExplanation"],
					'basicExplanationDetail' => $this->CRITERION_DETAILS[$criterionCode]["basicExplanationDetail"],
					'premiumExplanation' => $this->CRITERION_DETAILS[$criterionCode]["premiumExplanation"],
					'premiumExplanationDetail' => $this->CRITERION_DETAILS[$criterionCode]["premiumExplanationDetail"],
					'devExplanation' => $this->CRITERION_DETAILS[$criterionCode]["devExplanation"],
					'devExplanationDetail' => $this->CRITERION_DETAILS[$criterionCode]["devExplanationDetail"],
				];
				break;


			case "http_security_headers":
				// CRITERION LOGIC: Check for security headers and provide a criterion code
				$headers = $this->response["headers"];

				$hasStrictTransportSecurity = isset($headers["strict-transport-security"]);
				$hasXContentTypeOptions = isset($headers["x-content-type-options"]);
				$hasXFrameOptions = isset($headers["x-frame-options"]);
				$hasXXSSProtection = isset($headers["x-xss-protection"]);

				$presentHeaders = [];
				$missingHeaders = [];

				if ($hasStrictTransportSecurity) {
					$presentHeaders[] = "Strict-Transport-Security";
				} else {
					$missingHeaders[] = "Strict-Transport-Security";
				}

				if ($hasXContentTypeOptions) {
					$presentHeaders[] = "X-Content-Type-Options";
				} else {
					$missingHeaders[] = "X-Content-Type-Options";
				}

				if ($hasXFrameOptions) {
					$presentHeaders[] = "X-Frame-Options";
				} else {
					$missingHeaders[] = "X-Frame-Options";
				}

				if ($hasXXSSProtection) {
					$presentHeaders[] = "X-XSS-Protection";
				} else {
					$missingHeaders[] = "X-XSS-Protection";
				}

				$headerCount = count($presentHeaders);

				if ($headerCount == 4) {
					// All headers present
					$criterionCode = "HEADERS_HTTPSECURITY_HEADERS_ALL";
				} elseif ($headerCount > 0) {
					// Some headers present
					$criterionCode = "HEADERS_HTTPSECURITY_HEADERS_SOME";
				} else {
					// No headers present
					$criterionCode = "HEADERS_HTTPSECURITY_HEADERS_NONE";
				}

				// Save the criterion code
				$this->criterionScores[$category][$criterion] = [
					'score' => $this->CRITERION_DETAILS[$criterionCode]["score"],
					'maxScore' => $this->CRITERION_DETAILS[$criterionCode]["maxScore"],
					'basicExplanation' => $this->CRITERION_DETAILS[$criterionCode]["basicExplanation"],
					'basicExplanationDetail' => $this->CRITERION_DETAILS[$criterionCode]["basicExplanationDetail"],
					'premiumExplanation' => $this->CRITERION_DETAILS[$criterionCode]["premiumExplanation"],
					'premiumExplanationDetail' => $this->CRITERION_DETAILS[$criterionCode]["premiumExplanationDetail"],
					'devExplanation' => $this->CRITERION_DETAILS[$criterionCode]["devExplanation"],
					'devExplanationDetail' => $this->CRITERION_DETAILS[$criterionCode]["devExplanationDetail"],
				];
				break;




			case "cookie_security":
				$secureCookies = false;
				$httpOnlyCookies = false;

				// Check if there are Set-Cookie headers in the response
				if (isset($this->response["headers"]["set-cookie"])) {
					$cookieHeaders = $this->response["headers"]["set-cookie"];

					foreach ($cookieHeaders as $cookieHeader) {
						// Check if the Secure attribute is present
						if (stripos($cookieHeader, "Secure") !== false) {
							$secureCookies = true;
						}

						// Check if the HttpOnly attribute is present
						if (stripos($cookieHeader, "HttpOnly") !== false) {
							$httpOnlyCookies = true;
						}
					}
				}

				if ($secureCookies && $httpOnlyCookies) {
					$criterionCode = "HEADERS_COOKIESECURITY_BOTH";
				} elseif ($secureCookies) {
					$criterionCode = "HEADERS_COOKIESECURITY_SECURE";
				} else {
					$criterionCode = "HEADERS_COOKIESECURITY_NONE";
				}

				// Save the criterion code
				$this->criterionScores[$category][$criterion] = [
					'score' => $this->CRITERION_DETAILS[$criterionCode]["score"],
					'maxScore' => $this->CRITERION_DETAILS[$criterionCode]["maxScore"],
					'basicExplanation' => $this->CRITERION_DETAILS[$criterionCode]["basicExplanation"],
					'basicExplanationDetail' => $this->CRITERION_DETAILS[$criterionCode]["basicExplanationDetail"],
					'premiumExplanation' => $this->CRITERION_DETAILS[$criterionCode]["premiumExplanation"],
					'premiumExplanationDetail' => $this->CRITERION_DETAILS[$criterionCode]["premiumExplanationDetail"],
					'devExplanation' => $this->CRITERION_DETAILS[$criterionCode]["devExplanation"],
					'devExplanationDetail' => $this->CRITERION_DETAILS[$criterionCode]["devExplanationDetail"],
				];
				break;



			case "cors":
				$goodCors = false;

				// Check if there are headers containing the substring "cross-origin" in the response
				foreach ($this->response["headers"] as $header => $value) {
					// Ensure $header and $value are strings
					if (!is_string($header) || !is_string($value)) {
						continue; // Skip this iteration if $header or $value is not a string
					}

					// Perform a case-insensitive check for the presence of the substring "cross-origin" in header names or values
					if (stripos($header, "cross-origin") !== false || stripos($value, "cross-origin") !== false) {
						// You can perform additional checks here based on your criteria
						// For example, check if the allowed origin is specific or a wildcard "*"
						$goodCors = true;

						// Break out of the loop once a match is found, as we only need one occurrence
						break;
					}
				}

				if ($goodCors) {
					// If headers with "cross-origin" are found, consider it as good CORS
					$criterionCode = "HEADERS_CORS_TRUE";
				} else {
					// If no headers with "cross-origin" are found, mark it as unable to verify CORS
					$criterionCode = "HEADERS_CORS_FALSE";
				}

				// Save the criterion code
				$this->criterionScores[$category][$criterion] = [
					'score' => $this->CRITERION_DETAILS[$criterionCode]["score"],
					'maxScore' => $this->CRITERION_DETAILS[$criterionCode]["maxScore"],
					'basicExplanation' => $this->CRITERION_DETAILS[$criterionCode]["basicExplanation"],
					'basicExplanationDetail' => $this->CRITERION_DETAILS[$criterionCode]["basicExplanationDetail"],
					'premiumExplanation' => $this->CRITERION_DETAILS[$criterionCode]["premiumExplanation"],
					'premiumExplanationDetail' => $this->CRITERION_DETAILS[$criterionCode]["premiumExplanationDetail"],
					'devExplanation' => $this->CRITERION_DETAILS[$criterionCode]["devExplanation"],
					'devExplanationDetail' => $this->CRITERION_DETAILS[$criterionCode]["devExplanationDetail"],
				];
				break;



			case "cache_control":
				$goodCacheControl = false;

				// Check if the Cache-Control header is present in the response
				if (!empty($this->response["headers"]["cache-control"])) {
					// You can perform additional checks here based on your criteria
					// For example, check if specific cache directives are present
					if (true /* implement logic */) {
						$goodCacheControl = true;
					}
				}

				if ($goodCacheControl) {
					$criterionCode = "HEADERS_CACHECONTROL_GOOD";
				} else {
					$criterionCode = "HEADERS_CACHECONTROL_NONE";
				}

				// Save the criterion code
				$this->criterionScores[$category][$criterion] = [
					'score' => $this->CRITERION_DETAILS[$criterionCode]["score"],
					'maxScore' => $this->CRITERION_DETAILS[$criterionCode]["maxScore"],
					'basicExplanation' => $this->CRITERION_DETAILS[$criterionCode]["basicExplanation"],
					'basicExplanationDetail' => $this->CRITERION_DETAILS[$criterionCode]["basicExplanationDetail"],
					'premiumExplanation' => $this->CRITERION_DETAILS[$criterionCode]["premiumExplanation"],
					'premiumExplanationDetail' => $this->CRITERION_DETAILS[$criterionCode]["premiumExplanationDetail"],
					'devExplanation' => $this->CRITERION_DETAILS[$criterionCode]["devExplanation"],
					'devExplanationDetail' => $this->CRITERION_DETAILS[$criterionCode]["devExplanationDetail"],
				];
				break;


			default:
				// Handle unknown criteria
				break;
		}

		return $this->CRITERION_DETAILS[$criterionCode]["score"];
	}



	/**
	 * Check for disqualifying criteria in any category.
	 * If a disqualifying criterion is found, set the entire category score to 0 and the total score to 0.
	 */
	private function checkDisqualifyingCriteria()
	{
		foreach ($this->categoryScores as $category => $categoryData) {
			foreach ($categoryData['criterions'] as $criterionData) {
				if ($criterionData['score'] === -100) {
					$this->totalScore = 0;
					$this->categoryScores[$category]['category_score'] = 0;
					break; // No need to check further criteria in this category
				}
			}
		}
	}

	/**
	 * Check for uncertainty criteria in any category.
	 * If an uncertainty criterion is found, set the entire category score to -1 and the total score to -1.
	 */
	private function checkUncertaintyCriteria()
	{
		foreach ($this->categoryScores as $category => $categoryData) {
			foreach ($categoryData['criterions'] as $criterionData) {
				if ($criterionData['score'] === -1) {
					$this->totalScore = -1;
					$this->categoryScores[$category]['category_score'] = -1;
					break; // No need to check further criteria in this category
				}
			}
		}
	}
	private function injectDynamicValuesIntoCriterionScores()
	{
		$keysToModify = [];

		// Collect all keys that need modification
		foreach ($this->categoryScores as $category => $categoryData) {
			if (isset($categoryData["criterions"])) {
				foreach ($categoryData["criterions"] as $criterion => $details) {
					foreach ($details as $key => $value) {
						if (is_string($value)) {
							$keysToModify[] = [$category, $criterion, $key];
						}
					}
				}
			}
		}

		// Modify the values
		foreach ($keysToModify as $keys) {
			$category = $keys[0];
			$criterion = $keys[1];
			$key = $keys[2];
			$value = $this->categoryScores[$category]["criterions"][$criterion][$key];
			$this->categoryScores[$category]["criterions"][$criterion][$key] = $this->injectDynamicValues($value, $this->dynamicValues);
		}
	}




	private function injectDynamicValues($string, $values)
	{
		foreach ($values as $key => $value) {
			$string = str_replace("[$key]", $value, $string);
		}
		return $string;
	}
}
