<?php

namespace Validator;

class URL
{
    /**
     * @var array|false|string $parsedUrl The URL after parse_url() method.
     */
    private $parsedUrl;

    /**
     * @var string $urlInput The URL input to be validated and normalized.
     */
    public string $urlInput;

    /**
     * @var string $hostInputWithoutDots The host part of the URL input to be validated as not containing "gov".
     */
    private string $hostInputWithoutDots;

    /**
     * @var string $urlRegex The regular expression pattern for validating URL format.
     */
    private string $urlRegex = '/^(https?:\/\/)?([a-z\d-]+\.)*[a-z\d-]+(\.[a-z]{2,})(:\d+)?([\/\w \.-:])*?(\?\S*)?$/i';

    /**
     * Constructor for the LookupFormValidation class.
     *
     * @param string $input The input URL to be validated.
     */
    public function __construct($input)
    {
        $this->urlInput = $input;
    }

    public function Validate()
    {
        $this->urlInput = strtolower(trim($this->urlInput));

        // Check if the input is empty after trimming
        if ($this->urlInput === "") {
            return "EMPTY";
        }

        // Add 'http://' if the scheme is missing
        if (!preg_match('#^https?://#', $this->urlInput)) {
            $this->urlInput = 'https://' . $this->urlInput;
        }

        $this->parsedUrl = parse_url($this->urlInput);

        // Check if parsing was successful
        if ($this->parsedUrl === false) {
            return "INVALID";
        }

        // Validate the URL format (excluding the path section)
        if (!preg_match($this->urlRegex, $this->parsedUrl['host'] ?? '')) {
            return "INVALID";
        }

        // Replace dots with spaces in the host
        $host = isset($this->parsedUrl['host']) ? $this->parsedUrl['host'] : '';
        $this->hostInputWithoutDots = str_replace('.', ' ', $host);

        if ($this->ContainsGov($this->hostInputWithoutDots)) {
            return "GOV";
        }

        return 1;
    }
    private function ContainsGov(string $input): bool
    {
        // Split the string into words
        $words = preg_split('/\s+/', $input);

        // Count the number of words
        $wordCount = count($words);

        // Check if the last or second-to-last word is "gov"
        return ($wordCount >= 2 && (strtolower($words[$wordCount - 1]) === 'gov' || strtolower($words[$wordCount - 2]) === 'gov'));
    }
}
