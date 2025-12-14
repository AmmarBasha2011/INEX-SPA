<?php

/**
 * Main entry point for client-side navigation and routing requests.
 *
 * This script is responsible for capturing client-side routing requests, which are
 * typically sent with a `route` parameter in the query string. It includes the
 * main page rendering logic from `getPage.php` and invokes the `getPage()` function
 * to handle the actual rendering of the requested route.
 */
require_once 'getPage.php';

if (isset($_GET['route'])) {
    getPage($_GET['route']);
}
