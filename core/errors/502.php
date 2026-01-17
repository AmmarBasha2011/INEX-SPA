<?php
/**
 * Renders the HTTP 502 Bad Gateway error page.
 *
 * This page is displayed when the server, while acting as a gateway or proxy,
 * received an invalid response from an upstream server it accessed to fulfill the
 * request. It informs the user about the gateway issue and provides a link to
 * return to the homepage.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>502 Bad Gateway</title>
    <link rel="stylesheet" href="<?php echo getEnvValue('WEBSITE_URL'); ?>errors/style.css">
</head>
<body>
    <h1>502</h1>
    <p>Oops! The server received an invalid response from an upstream server.</p>
    <button onclick="redirect('')">Go to Home</button>
</body>
</html>
