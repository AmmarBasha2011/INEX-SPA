<?php
/**
 * Renders the 411 Length Required error page.
 *
 * This script is displayed when the server rejects a request because it
 * requires a `Content-Length` header, which was not provided. This is
 * common for POST or PUT requests that need to know the size of the
 * incoming request body.
 *
 * @var string $WEBSITE_URL The base URL of the website, used for linking
 *                          to CSS files and the homepage.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>411 Length Required</title>
    <link rel="stylesheet" href="<?php echo getEnvValue('WEBSITE_URL'); ?>errors/style.css">
</head>
<body>
    <h1>411</h1>
    <p>Length Required: The server refuses to accept the request without a defined Content-Length.</p>
    <button onclick="redirect('')">Go to Home</button>
</body>
</html>
