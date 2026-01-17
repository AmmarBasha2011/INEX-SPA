<?php
/**
 * Renders the HTTP 408 Request Timeout error page.
 *
 * This page is displayed when the server did not receive a complete request from
 * the client within the time that it was prepared to wait. It suggests a potential
 * network issue and provides a link to return to the homepage and try again.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>408 Request Timeout</title>
    <link rel="stylesheet" href="<?php echo getEnvValue('WEBSITE_URL'); ?>errors/style.css">
</head>
<body>
    <h1>408</h1>
    <p>Oops! The server timed out waiting for the request.</p>
    <button onclick="redirect('')">Go to Home</button>
</body>
</html>
