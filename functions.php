<?php

/**
 * --------------------------------------------------------------------------
 * Application Functions
 * --------------------------------------------------------------------------
 *
 * This file is a good place to define any global functions that your
 * application uses. These functions can be called directly from your
 * '.ahmed.php' templates or used as handlers for 'command' type routes
 * defined in `routes.php`.
 *
 * Example of a function that could be used as a route handler:
 *
 * function handle_contact_form() {
 *     // Process POST data, send email, etc.
 *     if (\$_SERVER['REQUEST_METHOD'] === 'POST') {
 *         // \$name = \$_POST['name'] ?? 'Guest';
 *         // error_log("Contact form submitted by " . \$name);
 *         // For demonstration:
 *         echo "Contact form processed (demonstration).";
 *     } else {
 *         echo "Please submit the form.";
 *     }
 * }
 *
 * You would then register this in routes.php like so:
 *
 * \$routes[] = [
 *     'path' => '/contact-submit',
 *     'method' => 'POST',
 *     'type' => 'command',
 *     'handler' => 'handle_contact_form',
 *     'is_api' => true, // Or false if it renders a full page message
 * ];
 *
 */

// Define your application's global functions below this line.

?>
