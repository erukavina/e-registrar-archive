<?php

namespace AWS;

use Aws\Ses\SesClient;
use Aws\Exception\AwsException;

class Mail
{
    private $awsAccessKeyId;
    private $awsSecretAccessKey;
    private $mail;
    // Constructor
    public function __construct()
    {
        \Document\Log::Log("AWS\Mail - Initializing Mail class.");
        $AWS_KEY_ID = "AWS_KEY_ID";
        $AWS_SECRET_KEY = "AWS_SECRET_KEY";

        $this->awsAccessKeyId = $AWS_KEY_ID;
        $this->awsSecretAccessKey = $AWS_SECRET_KEY;

        $this->initializeMailClient();
    }

    // Method to initialize the SES client
    private function initializeMailClient()
    {
        \Document\Log::Log("AWS\Mail - Initializing SES client.");
        try {
            $this->mail = new SesClient([
                'region' => "eu-west-1",
                'version' => 'latest',
                'credentials' => [
                    'key' => $this->awsAccessKeyId,
                    'secret' => $this->awsSecretAccessKey,
                ],
            ]);
            \Document\Log::Log("AWS\Mail - SES client initialized successfully.");
        } catch (AwsException $e) {
            \Document\Log::Log("AWS\Mail - Failed to initialize SES client: " . $e->getMessage());
        }
    }

    // Method to send email
    public function SendMail(
        $recipient,
        $subject,
        $plaintext_body,
        $html_body
    ) {
        \Document\Log::Log("AWS\Mail - Preparing to send email to $recipient.");
        $sender_email = 'no-reply@e-registrar.com';
        $char_set = 'UTF-8';
        try {
            $result = $this->mail->sendEmail([
                'Destination' => [
                    'ToAddresses' => [$recipient],
                ],
                'Source' => $sender_email,
                'Message' => [
                    'Body' => [
                        'Html' => [
                            'Charset' => $char_set,
                            'Data' => $html_body,
                        ],
                        'Text' => [
                            'Charset' => $char_set,
                            'Data' => $plaintext_body,
                        ],
                    ],
                    'Subject' => [
                        'Charset' => $char_set,
                        'Data' => $subject,
                    ],
                ],
            ]);
            \Document\Log::Log("AWS\Mail - Email sent successfully to $recipient.");
            return 1;
        } catch (AwsException $e) {
            \Document\Log::Log("AWS\Mail - Failed to send email to $recipient: " . $e->getMessage());
            return 0;
        }
    }
}
