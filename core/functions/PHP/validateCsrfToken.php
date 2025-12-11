<?php

/**
 * Contains the function for validating CSRF tokens.
 */
session_start();

/**
 * Validates the CSRF token submitted in a POST request.
 *
 * This function checks if the `csrf_token` in the `$_POST` data matches the
 * token in the current session. If the token is missing or does not match,
 * the script will terminate with an error message.
 *
 * @return void
 */
function validateCsrfToken()
{
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        exit('CSRF validation failed!');
    }
}
