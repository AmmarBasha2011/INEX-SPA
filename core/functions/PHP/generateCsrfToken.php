<?php

/**
 * Generates and retrieves a Cross-Site Request Forgery (CSRF) token for the current session.
 *
 * This function ensures that a unique, cryptographically secure token is associated with the
 * user's session. If a token does not already exist in the session, a new one is generated
 * using `random_bytes`. This token should be included in forms and AJAX requests to prevent
 * CSRF attacks, where it can be validated by the `validateCsrfToken` function.
 *
 * @return string The CSRF token stored in the session.
 */
function generateCsrfToken()
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Secure random token
    }

    return $_SESSION['csrf_token'];
}
