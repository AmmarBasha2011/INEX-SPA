<?php

/**
 * Generates a CSRF token and stores it in the session.
 *
 * @return string The generated CSRF token.
 */
function generateCsrfToken()
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}
