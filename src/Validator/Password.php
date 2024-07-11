<?php

namespace Validator;

class Password
{
    /**
     * Validate the given password.
     *
     * @param string $password The password to validate.
     * @return bool True if the password passes validation, false otherwise.
     */
    public static function Validate($password)
    {
        // Validate length
        if (strlen($password) < 8) {
            return false;
        }

        // Validate at least one letter and one special character
        if (!preg_match('/[a-zA-Z]/', $password) || !preg_match('/[^a-zA-Z0-9]/', $password)) {
            return false;
        }
        
        // Password is valid
        return true;
    }
}
