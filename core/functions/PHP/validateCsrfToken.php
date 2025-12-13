<?php

/**
 * CSRF Token Validation
 *
 * This file contains the function for validating CSRF tokens to protect
 * against Cross-Site Request Forgery attacks. It ensures that incoming
 * POST requests originate from the application's own forms.
 */
session_start();

/**
 * Validates the CSRF token from a POST request against the session.
 *
 * This function checks if a 'csrf_token' key exists in the `$_POST` superglobal
 * and compares its value with the token stored in `$_SESSION['csrf_token']`.
 * If the token is missing, invalid, or does not match, the function terminates
 * the script execution with a "CSRF validation failed!" message.
 *
 * This should be called at the beginning of any script that processes form
 * submissions or state-changing actions to ensure the request is legitimate.
 *
 * @return void Terminates script with an error message on failure.
 */
function validateCsrfToken()
{
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        // In a real application, you might want to handle this more gracefully,
        // such as logging the attempt and showing a user-friendly error page.
        exit('CSRF validation failed!');
    }
}
