<?php

/**
 * --------------------------------------------------------------------------
 * Web Routes (New System)
 * --------------------------------------------------------------------------
 *
 * These routes are used by the new routing system when USE_NEW_ROUTES=true
 * in your .env file (or if the variable is not set, as true is the default).
 *
 */

/**
 * --------------------------------------------------------------------------
 * Web Routes
 * --------------------------------------------------------------------------
 *
 * Here is where you can register web routes for your application. These
 * routes are loaded by the Router class. Now create something great!
 *
 * Example:
 *
 * \$routes[] = [
 *     'path' => '/',
 *     'method' => 'GET',
 *     'type' => 'preview', // 'preview' or 'command'
 *     'handler' => 'index.ahmed.php', // For 'preview': path to template in web/
 *                                    // For 'command': function name or callable
 *     // 'is_api' => false, // Optional: true if this is an API endpoint
 *     // 'dynamic_segment' => null, // Optional: name of the dynamic part if path is e.g. /product/{id}
 * ];
 *
 */

\$routes = [];

// Add your routes below this line

// --- Routes migrated from getPage.php ---

// Homepage
\$routes[] = [
    'path' => '/',
    'method' => 'GET',
    'type' => 'preview',
    'handler' => 'index.ahmed.php', // Assuming web/index.ahmed.php is the homepage template
];

// CSRF Token Fetching
\$routes[] = [
    'path' => '/fetchCsrfToken',
    'method' => 'GET', // Or 'POST', confirm based on actual usage if possible. Assuming GET.
    'type' => 'command',
    'handler' => 'generateCsrfToken', // Assumes generateCsrfToken() is globally available from index.php
    'is_api' => true,
];

// Blocked Page Display
\$routes[] = [
    'path' => '/blocked',
    'method' => 'GET',
    'type' => 'command',
    'handler' => function() {
        // This handler assumes loadScripts() is globally available if error pages need it
        // and that core/errors/403.php exists.
        http_response_code(403);
        if (function_exists('loadScripts')) { // loadScripts is now in index.php
            loadScripts();
        }
        if (file_exists('core/errors/403.php')) {
            include 'core/errors/403.php';
        } else {
            echo "Error 403: Access Forbidden."; // Fallback
        }
        exit;
    },
    'is_api' => false, // It serves an HTML error page
];

// Special JS File Route (getWEBSITEURLValue.js)
\$routes[] = [
    'path' => '/JS/getWEBSITEURLValue.js',
    'method' => 'GET',
    'type' => 'command',
    'handler' => 'getWEBSITEURLValue', // Assumes getWEBSITEURLValue() is globally available from index.php
    'is_api' => true, // Outputs JavaScript content directly
];

// Language Setting Route
\$routes[] = [
    'path' => '/setLanguage',
    'method' => 'POST',
    'type' => 'command',
    'handler' => function() {
        if (getEnvValue('DETECT_LANGUAGE') == 'true' && isset(\$_POST['lang'])) {
            \$lang = \$_POST['lang'];
            // Ensure setcookie parameters are correct and secure if needed (e.g., HttpOnly, Secure)
            setcookie('lang', \$lang, time() + (86400 * 30), "/"); // 30 days

            // After setting cookie, redirect to home or previous page?
            // Original code didn't specify. A common practice is to redirect.
            // For now, let's keep it simple and not redirect from here.
            // A success message or status could be returned if it's an AJAX call.
            echo "Language set."; // Placeholder response
        } else {
            // Handle error or invalid request
            http_response_code(400); // Bad Request
            echo "Failed to set language. 'lang' parameter missing or feature disabled.";
        }
        exit;
    },
    'is_api' => true, // Typically, this would be an AJAX call or not return a full page
];

// --- End of migrated routes ---
?>
