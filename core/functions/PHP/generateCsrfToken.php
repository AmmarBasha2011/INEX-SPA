<?php

/**
 * Generates and stores a CSRF token in the session.
 *
 * If a CSRF token is not already present in the current session, this function
 * creates a cryptographically secure random token and stores it in `$_SESSION['csrf_token']`.
 * It then returns the token.
 *
 * @return string The CSRF token for the current session.
 */
function generateCsrfToken()
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Secure random token
    }

    return $_SESSION['csrf_token'];
}
