<?php

/**
 * Author: Emanuel Tin Rukavina <emanuel@uncuni.com>
 * Handles rendering of the head, navigation, and footer of the website.
 */

namespace Document;

use Document\Database;

class Document
{
    /** @var string|null The title of the document. */
    public ?string $title;

    /** @var string|null The description of the document. */
    public ?string $description;

    const DocumentKeywords =
    "e-Registrar, e-registrar, registrar, trustscore, eregistrar, internet, privacy, TrustScore, 
    safety, online safety, website lookup";
    /**
     * Initialize document head and other data
     */
    public function __construct()
    {
        session_start();


        $current_url = "http" . (($_SERVER['SERVER_PORT'] ?? 80) == 443 ? "s://" : "://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        Log::Log("Document\Document -  $current_url");

        if (!isset($_SESSION['document'])) {

            Log::Log("Document\Document - Creating a document session because it doesn't exist.");
            $REMOTE_ADDR = self::GetIPAddress();
            Log::Log("Document\Document - IP: $REMOTE_ADDR");
            if ($_SERVER['HTTP_HOST'] == "localhost") {
                $REMOTE_ADDR = "67.249.195.53";
                $_SESSION["localhost"] = true;
                Log::Log("Document\Document - LOCALHOST / DEBUG SESSION STARTED (DEV)");
            }

            $device = self::GetDeviceData();
            $geo = \ThirdPartyAPI\IPtoGeo::IPtoGeo($REMOTE_ADDR);
            if (empty($geo)) {
                $geo = array(
                    "country" => "UNKNOWN",
                );
            }

            // Create $document_session array
            $document_session = [
                "geo" => $geo,
                "REMOTE_ADDR" => $REMOTE_ADDR,
                "device_type" => $device["device_type"],
                "device_info" => $device["device_info"],
            ];
            $_SESSION['document'] = $document_session;
            Log::Log("Document\Document - Document sessions created with the following values:<pre>" . json_encode($document_session, JSON_PRETTY_PRINT) . "</pre>");
        }
    }

    /**
     * Retrieves user's device information.
     *
     * @return array User's device information.
     */
    private function GetDeviceData()
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        if (strpos($user_agent, 'Mobile') !== false) {
            $device_type = "phone";
        } else {
            $device_type = "computer";
        }
        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        // Get browser name
        if (strpos($user_agent, 'Firefox') !== false) {
            $browser_name = 'Mozilla Firefox';
        } elseif (strpos($user_agent, 'OPR') !== false || strpos($user_agent, 'Opera') !== false) {
            $browser_name = 'Opera';
        } elseif (strpos($user_agent, 'Edge') !== false) {
            $browser_name = 'Microsoft Edge';
        } elseif (strpos($user_agent, 'Chrome') !== false) {
            $browser_name = 'Google Chrome';
        } elseif (strpos($user_agent, 'Safari') !== false) {
            $browser_name = 'Apple Safari';
        } else {
            $browser_name = 'Unknown';
        }

        // Get device
        if (preg_match('/Linux/', $user_agent)) {
            $device_info = 'Linux';
        } elseif (preg_match('/Windows/', $user_agent)) {
            $device_info = 'Windows';
        } elseif (preg_match('/Macintosh/', $user_agent)) {
            $device_info = 'Macintosh';
        } elseif (preg_match('/Android/', $user_agent)) {
            $device_info = 'Android';
        } elseif (preg_match('/iPhone/', $user_agent)) {
            $device_info = 'iPhone';
        } elseif (preg_match('/iPad/', $user_agent)) {
            $device_info = 'iPad';
        } else {
            $device_info = 'Unknown';
        }

        $device_info = $browser_name . ' on ' . $device_info;
        return $data = [
            "device_type" => $device_type,
            "device_info" => $device_info
        ];
    }
    public function GetIPAddress()
    {
        //whether ip is from the share internet  
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        //whether ip is from the proxy  
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        //whether ip is from the remote address  
        else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }




    /**
     * Sets the title of the document.
     *
     * @param string $title The title of the document.
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Sets the description of the document.
     *
     * @param string $description The description of the document.
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Sets the URL of the document.
     *
     * @param string $url The URL of the document.
     */
    private function setUrl()
    {
        // Check if the request is secure or not
        $isHttps = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on');
        $protocol = $isHttps ? "https://" : "https://";

        // Get the host
        $host = $_SERVER['HTTP_HOST'];

        // Get the current path
        $path = $_SERVER['REQUEST_URI'];

        // Combine all parts to get the complete URL
        return $protocol . $host . $path;
    }

    /**
     * Renders the HTML head section of the document.
     */
    public function RenderHead()
    {
        if (empty($this->title)) {
            $title = "e-Registrar";
        } else {
            $title = $this->title . " | e-Registrar";
        }

        $description = $this->description;
        $keywords = self::DocumentKeywords;
        $url = self::setUrl();
        include_once 'shared_html/head.php';
    }

    /**
     * Renders the navigation section of the document.
     */
    public function RenderNavigation()
    {
        include_once 'shared_html/header.php';
    }

    /**
     * Renders the footer section of the document.
     */
    public function RenderFooter()
    {
        include_once 'shared_html/footer.php';
    }
}
