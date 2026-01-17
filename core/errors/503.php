<?php
/**
 * Renders the HTTP 503 Service Unavailable error page.
 *
 * This page indicates that the server is temporarily unable to handle the request
 * due to maintenance or being overloaded. It suggests that the service will be
 * available again later and provides a link for the user to return to the homepage.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>503 Service Unavailable</title>
    <link rel="stylesheet" href="<?php echo getEnvValue('WEBSITE_URL'); ?>errors/style.css">
</head>
<body>
    <h1>503</h1>
    <p>Sorry! The server is temporarily unavailable due to maintenance or overload.</p>
    <button onclick="redirect('')">Go to Home</button>
</body>
</html>
