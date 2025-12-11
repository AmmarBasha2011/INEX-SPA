<?php

/**
 * Handles URL routing for the application.
 *
 * This script checks for a 'route' parameter in the GET request and,
 * if present, passes it to the `getPage()` function to render the
 * appropriate page.
 */
require_once 'getPage.php';

if (isset($_GET['route'])) {
    getPage($_GET['route']);
}
