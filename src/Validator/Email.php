<?php

namespace Validator;

class Email
{
    /**
     * Validate the given email.
     *
     * @param string $email The email to validate.
     * @return bool True if the email passes validation, false otherwise.
     */
    public static function Validate($email) : bool
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        if (strpos($email, '+') !== false) {
            return false;
        }
        // Password is valid
        return true;
    }
}
