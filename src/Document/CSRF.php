<?php
namespace Document;

/**
 * (c) Emanuel Tin Rukavina <emanuel@uncuni.com>
 */
class CSRF
{
    /**
     * Constructor
     *
     * Initiates the session and generates a CSRF token.
     */
    public function __construct()
    {
        // Start a session so that the CSRF session can be set in generateToken()
        $this->GenerateToken();
    }

    /**
     * Generate Token
     *
     * Generates a CSRF token and sets it in the session if it doesn't exist.
     *
     * @return string The generated CSRF token.
     */
    public function GenerateToken()
    {
        // If the session doesn't exist, create it and generate a CSRF token
        if (!isset($_SESSION['document']['csrf'])) {
            Log::Log("Document\CSRF - Generating a CSRF token because the session doesn't exist.");
            $token = bin2hex(random_bytes(32));
            $_SESSION['document']['csrf'] = $token;
            return $token;
        }

        // If the session already exists, return the existing CSRF token
        return $_SESSION['document']['csrf'];
    }

    /**
     * Check Token
     *
     * Checks if the submitted CSRF token matches the one stored in the session.
     *
     * @param string $submittedToken The CSRF token submitted with the request.
     * @return bool True if the tokens match, false otherwise.
     */
    public function CheckToken($submittedToken)
    {
        // Check if the session exists and if the submitted token matches
        if (isset($_SESSION['document']['csrf']) && $submittedToken === $_SESSION['document']['csrf']) {
            return true;
        }

        // If the session or token doesn't match, return false
        Log::Log("Document\CSRF - The CSRF token check failed.");
        return false;
    }
}
