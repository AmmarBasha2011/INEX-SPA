<?php
/**
 * Renders the HTTP 400 Bad Request error page.
 *
 * This page is displayed when the server cannot process a request due to a client
 * error, such as malformed request syntax, invalid request message framing, or
 * deceptive request routing. It provides a user-friendly message and a link
 * to navigate back to the homepage.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>400 Bad Request</title>
    <link rel="stylesheet" href="<?php echo getEnvValue('WEBSITE_URL'); ?>errors/style.css">
</head>
<body>
    <h1>400</h1>
    <p>Oops! The server could not understand your request due to malformed syntax.</p>
    <button onclick="redirect('')">Go to Home</button>
</body>
</html>
