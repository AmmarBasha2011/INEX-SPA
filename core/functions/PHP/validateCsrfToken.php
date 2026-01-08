<?php

/**
 * Validates a Cross-Site Request Forgery (CSRF) token submitted with a POST request.
 *
 * This function is a critical security measure to prevent CSRF attacks. It must be called
 * at the beginning of any script that handles form submissions or performs state-changing
 * actions.
 *
 * The function works by comparing the `csrf_token` value received in the `$_POST` data
 * with the token stored in the user's session (`$_SESSION['csrf_token']`). To prevent
 * timing attacks, the comparison is done using the constant-time `hash_equals` function.
 *
 * If the token is missing from either `$_POST` or `$_SESSION`, or if the tokens do not
 * match, the function will immediately terminate the script with a 403 Forbidden HTTP
 * response code and an error message. This ensures that unauthorized or malicious
 * requests are stopped before any sensitive operations can be performed.
 *
 * @return void This function does not return a value. It either allows the script to
 *              continue execution upon successful validation or terminates it on failure.
 */
function validateCsrfToken()
{
    if (!isset($_SESSION['csrf_token']) || !isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        http_response_code(403);
        exit('CSRF validation failed!');
    }
}
