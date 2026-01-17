<?php
/**
 * Renders the HTTP 411 Length Required error page.
 *
 * This page is displayed when the server refuses to accept a request without a
 * defined `Content-Length` header. It informs the user that this header is
 * required for the request to be processed and provides a link back to the
 * homepage.
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
