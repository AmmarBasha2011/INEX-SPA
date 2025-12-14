<?php

/**
 * Provides the function for validating Cross-Site Request Forgery (CSRF) tokens.
 *
 * This file is a critical part of the application's defense against CSRF attacks,
 * ensuring that state-changing requests originate from the application's own forms.
 */

/**
 * Validates a CSRF token submitted with a POST request against the one stored in the session.
 *
 * This function should be called at the beginning of any script that processes form
 * submissions or executes state-changing actions. It compares the `csrf_token` value
 * from the `$_POST` array with the token stored in `$_SESSION['csrf_token']`. If the
 * tokens are missing or do not match, it terminates the script execution with an
 * error message to prevent the malicious request from being processed.
 *
 * @return void
 */
function validateCsrfToken()
{
    if (!isset($_SESSION['csrf_token']) || !isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        http_response_code(403);
        exit('CSRF validation failed!');
    }
}
